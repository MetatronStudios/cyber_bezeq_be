<?php

namespace App\Repositories;

use App\User;
use App\Solution;
use App\FinalistScore;
use App\Riddle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository extends ParentRepository
{
    public function __construct()
    {
        $this->model = new User();
    }

    public function add($data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        try {
            $user = $this->model::create($data);
        } catch (\Exception $e) {  // @codeCoverageIgnore
            return $e; // @codeCoverageIgnore
        }
        return $user;
    }

    public function getByEmail($email)
    {
        return $this->model->where('email', $email)->first();
        //return $this->model->where('email', $email)->first();
    }

    public function update($item, $data)
    {
        return parent::update($item, self::fixPassword($data));
    }

    private function fixPassword($data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $data;
    }

    public function getAllPaginate($data)
    {
        $query = $this->model::query();
        $type = $data && $data['type'] ? $data['type'] : array('U', 'F');
        $query->whereIn('type', $type);

        if ($data && $data['name'] && $data['name'] != '') {
            $query->where('name', 'like', '%' . $data['name'] . '%');
        }
        if ($data && $data['email'] && $data['email'] != '') {
            $query->where('email', 'like', '%' . $data['email'] . '%');
        }

        $sort = $data && $data['sort'] ? $data['sort'] : 'id';
        $order = $data && $data['order'] ? $data['order'] : 'desc';
        return $query->orderBy($sort, $order)->paginate(10);
    }

    public function getWinners($data)
    {
        $q = Solution::query();
$q = $q->select(
    'id_user',
    'users.name as name',
    'users.email as email',
    // 'users.district as district',
    DB::raw('SUM(CASE WHEN is_correct = 1 THEN riddles.score ELSE 0 END) as total_score'),
    DB::raw('COUNT(CASE WHEN is_correct = 0 THEN 1 END) as wrong_solutions_count'),
    DB::raw('MAX(CASE WHEN is_correct = 1 THEN solutions.created_at ELSE null END) as last_correct_solution_time')
)
    ->join('riddles', 'solutions.id_riddle', '=', 'riddles.id')
    ->join('users', 'solutions.id_user', '=', 'users.id')
    ->groupBy('id_user');

$q->orderBy('total_score', 'desc')->orderBy('last_correct_solution_time', 'asc');
return $q->paginate(50);

    }


    public function getWinnersExport() {
        $q = Solution::query();
        $q = $q->select(
            'id_user',
            'users.name as name',
            'users.email as email',
            // 'users.district as district',
            DB::raw('SUM(CASE WHEN is_correct = 1 THEN riddles.score ELSE 0 END) as total_score'),
            DB::raw('COUNT(CASE WHEN is_correct = 0 THEN 1 END) as wrong_solutions_count'),
            DB::raw('MAX(CASE WHEN is_correct = 1 THEN solutions.created_at ELSE null END) as last_correct_solution_time')
        )
            ->join('riddles', 'solutions.id_riddle', '=', 'riddles.id')
            ->join('users', 'solutions.id_user', '=', 'users.id')
            ->groupBy('id_user');

        $q->orderBy('total_score', 'desc')->orderBy('last_correct_solution_time', 'asc');
        return $q->get();
    }
    public function getFinalistWinnersExport()
    {
        $q = FinalistScore::query();
        $q = $q->select(
            'id_user',
            'users.name as name',
            'users.email as email',
            // 'users.district as district',
            'answer',
            'score',
            'finalist_score.created_at as created_at'
        )
            ->join('users', 'finalist_score.id_user', '=', 'users.id');
        $q->orderBy('score', 'desc')->orderBy('created_at')->orderBy('id_user');
        return $q->get();
    }


    public function getWinnersMembers($data)
    {
        DB::statement("DROP temporary TABLE IF EXISTS `solutions_b`;");
        DB::statement("CREATE temporary TABLE IF NOT EXISTS `solutions_b` (
            `id` int(10) UNSIGNED NOT NULL,
            `id_user` int(11) NOT NULL,
            `id_member` int(11) NOT NULL,
            `id_riddle` int(11) NOT NULL,
            `answer` varchar(191) NOT NULL,
            `is_correct` int(11) NOT NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `solutions_b_id_id_user_id_riddle_id_member_is_correct_index` (`id`,`id_user`,`id_riddle`,`id_member`,`is_correct`)
          ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

        $result = "";
        $riddles = Riddle::get('id');
        foreach ($riddles as $riddle) {
            $id = $riddle->id;
            if ($id) {
                DB::statement("insert into solutions_b (SELECT * FROM `solutions` where is_correct='1' and id_riddle='" . $id . "' group by id_member)");
            }
        }
        DB::statement("DROP temporary TABLE IF EXISTS `solutions_grouped`;");
        DB::statement("CREATE temporary TABLE IF NOT EXISTS `solutions_grouped` (SELECT
            id_member as id_member,
            id_user as id,
            COUNT(id) as total
            FROM solutions_b
              GROUP BY id_member
            )");
        //$q = DB::select("select users.id,users.name,users.email,users.phone,solutions_grouped.total from `users` left join `solutions_grouped` on (solutions_grouped.id=users.id) order by total desc");
        $query = User::leftJoin('solutions_grouped', function ($join) {
            $join->on('users.id', '=', 'solutions_grouped.id');
        });
        $query = $query->select('users.id as id_user', 'members.id as id_member', 'members.name as name', 'age', 'users.name as lastName', 'email', 'phone', 'total')->leftJoin('members', function ($join) {
            $join->on('members.id', '=', 'solutions_grouped.id_member');
        });
        if ($data && $data['minimum'] && $data['minimum'] != '') {
            $query->where('total', '>=', $data['minimum']);
        }
        $query->orderBy('total', 'desc')->orderBy('solutions_grouped.id_member');
        return $query->paginate(50);
    }


    public function getFinalistWinners($data)
    {
        $query = FinalistScore::with('user');
        if ($data && $data['minimum'] && $data['minimum'] != '') {
            $query->where('score', '>=', $data['minimum']);
        }
        $query->orderBy('score', 'desc')->orderBy('created_at')->orderBy('id');
        return $query->paginate(50);
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }
}
