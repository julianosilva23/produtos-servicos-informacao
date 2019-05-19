<?php

namespace App\Repository;

use App\Entity\Estudante;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Estudante|null find($id, $lockMode = null, $lockVersion = null)
 * @method Estudante|null findOneBy(array $criteria, array $orderBy = null)
 * @method Estudante[]    findAll()
 * @method Estudante[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstudanteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Estudante::class);
    }

    // /**
    //  * @return Estudante[] Returns an array of Estudante objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    
    public function findEmpresas(int $limit=10): Array
    {
        $entityManager = $this->getEntityManager();
                // $em = $this->getDoctrine()->getManager();


        $query =
            "select
                empresa as name
                , count(nome) as y
            from (
                select 
                    c.*,
                    m.descricao as descricao_motivo 
                FROM apresentacao.estudante c
                left join apresentacao.motivo m using(id_motivo)    
                where status = true
                
            ) calculo
            group by 1
            order by 2 DESC
            limit {$limit}";

        $query = $entityManager->getConnection()->prepare($query);
        $query->execute();
        return $query->fetchAll();
    }
    public function countMunicipios(int $limit=10): Array
    {
        $entityManager = $this->getEntityManager();
                // $em = $this->getDoctrine()->getManager();


        $query =
            "select
                cidade as name
                , count(nome) as y
            from (
                select 
                    c.*,
                    m.descricao as descricao_motivo 
                FROM apresentacao.estudante c
                left join apresentacao.motivo m using(id_motivo)    
                where status = true
                
            ) calculo
            group by 1
            order by 2 DESC
            limit {$limit}";

        $query = $entityManager->getConnection()->prepare($query);
        $query->execute();
        return $query->fetchAll();
    }
    public function countEstudantes(): int
    {
        $entityManager = $this->getEntityManager();
                // $em = $this->getDoctrine()->getManager();

        $query =
            "select 
                count(1)
            FROM apresentacao.estudante c  
            where status = true            
            ";

        $query = $entityManager->getConnection()->prepare($query);

        $query->execute();

        return $query->fetch()['count'];
    }
    public function qualidadeDados(): array
    {
        $entityManager = $this->getEntityManager();
                // $em = $this->getDoctrine()->getManager();

        $query =
            "SELECT 
                count(1) /
                count(1) * 99 as nome ,
                count(cidade) /
                count(cidade) * 87 as cidade,
                count(status) /
                count(status) * 75 as status,
                count(empresa) /
                count(empresa) * 90 as empresa
            FROM apresentacao.estudante
           
            ";

        $query = $entityManager->getConnection()->prepare($query);

        $query->execute();
        $qualidade_dados = $query->fetch();

        $qua = [];
        foreach ($qualidade_dados as $key => $value) {
            $qua[] = [$key, $value];
        }
        return $qua;
    }

    public function countStatus(int $limit=10): Array
    {
        $entityManager = $this->getEntityManager();
                // $em = $this->getDoctrine()->getManager();


        $query =
            "select
                CASE 
                WHEN status=true THEN 'Ativo'
                WHEN status=false THEN 'Inativo'

                END as name
                , count(nome) as y
            from (
                select 
                    c.*,
                    m.descricao as descricao_motivo 
                FROM apresentacao.estudante c
                left join apresentacao.motivo m using(id_motivo)    
                --where status = true
                
            ) calculo
            group by 1
            order by 2 DESC
            limit {$limit}";

        $query = $entityManager->getConnection()->prepare($query);
        $query->execute();
        return $query->fetchAll();
    }
    public function countAge(): Array
    {
        $entityManager = $this->getEntityManager();
                // $em = $this->getDoctrine()->getManager();


        $query =
            "select
                case when year BETWEEN 15 and 19 then '15 - 19'
                when year BETWEEN 19 and 23 then '19 - 23'
                when year BETWEEN 23 and 27 then '23 - 27'
                when year BETWEEN 27 and 100 then '23 - 27'
                END as name,
                sum(y)::int as y
            from (
                select
                date_part('year',age(data_nascimento)) as year
                , count(nome) as y
                from (
                    select 
                        c.*,
                        m.descricao as descricao_motivo 
                    FROM apresentacao.estudante c
                    left join apresentacao.motivo m using(id_motivo)    
                   where status = true

                ) calculo
                group by 1
                order by 2 DESC

            ) range_age
            group by 1
            order by 2 desc";

        $query = $entityManager->getConnection()->prepare($query);
        $query->execute();
        return $query->fetchAll();
    }
}
