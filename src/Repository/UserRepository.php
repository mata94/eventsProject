<?php

namespace App\Repository;

use App\Entity\User;
use App\Model\FilterRequest;
use App\Model\Stats;

class UserRepository extends BaseRepository
{
   protected function getEntityName()
   {
       return User::class;
   }

    /**
     * @param string $email
     * @return User|null
     */
   public function findOneByEmail(string $email): ?User
   {
       return $this->findOneBy([
           "email" => $email
       ]);
   }

    /**
     * @param string $token
     * @return User|null
     */
   public function findOneByToken(string $token): ?User
   {
       return $this->findOneBy([
           "userToken" => $token
       ]);
   }

    /**
     * @param FilterRequest $filterRequest
     * @return User[]
     */
    public function getAll(FilterRequest $filterRequest): array
    {
        $query  = $this->getQuery($filterRequest);

        $dql = "SELECT u
                FROM " . $this->getEntityName(). " u "
            . $query['join']
            . " WHERE 1 = " . 1 . " "
            . $query['query'];

        $query = $this->em->createQuery($dql)
            ->setParameters($query['params']);

        /** @var User[] $result */
        $result = $query->getResult();
        return $result;
    }

    /**
     * @param FilterRequest|null $request
     * @return array{
     *       query: string,
     *       params: array<string, mixed>,
     *       join: string
     *   }
     */
    private function getQuery(FilterRequest $request = null): array
    {
        $params = [];
        $query  = '';
        $join   = [];

        if($request != null) {
            if ($search = $request->getSearch()) {
                $search = "'%" . $search . "%'";
                $query .= " AND (u.email LIKE " . $search . ')';
            }
        }

        return [
            'query'  => $query,
            'params' => $params,
            'join'   => implode("", $join)
        ];
    }

    /**
     * @param FilterRequest|null $request
     * @return Stats
     */
    public function getListStats(FilterRequest $request = null): Stats
    {
        $query  = $this->getQueryRaw($request);

        $results = $this->rawSql(
            "SELECT count(*) as total
                FROM user u "
            . $query['join']
            . " WHERE 1 = " . 1 . " "
            . $query['query'],
            $query['params']
        );

        return Stats::makeUser($results[0]["total"]);
    }

    /**
     * @param FilterRequest|null $request
     * @return array{
     *       query: string,
     *       params: array<string, mixed>,
     *       join: string
     *   }
     */
    private function getQueryRaw(FilterRequest $request = null): array
    {
        $params = [];
        $query  = '';
        $join   = [];

        if($request != null) {
            if ($search = $request->getSearch()) {
                $search = "'%" . $search . "%'";
                $query .= " AND (u.email LIKE " . $search . ')';
            }
        }

        return [
            'query'  => $query,
            'params' => $params,
            'join'   => join('', $join)
        ];
    }
}
