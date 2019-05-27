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
    public function estudantesMes(): array
    {
        $entityManager = $this->getEntityManager();
                // $em = $this->getDoctrine()->getManager();

        $query =
            "
            SELECT 
                ARRAY[
                    sum(jan),
                    sum(fev),
                    sum(mar),
                    sum(abr),
                    sum(mai),
                    sum(jun),
                    sum(jul),
                    sum(ago),
                    sum(set),
                    sum(out),
                    sum(nov),
                    sum(dez)
                ] as ativos,
                ARRAY[
                    'Jan',
                    'Fev',
                    'Mar',
                    'Abr',
                    'Mai',
                    'Jun',
                    'Jul',
                    'Ago',
                    'Set',
                    'Out',
                    'Nov',
                    'Dez'
                ] as meses,
                ARRAY[
                    total_estudantes - sum(jan),
                    total_estudantes - sum(fev),
                    total_estudantes - sum(mar),
                    total_estudantes - sum(abr),
                    total_estudantes - sum(mai),
                    total_estudantes - sum(jun),
                    total_estudantes - sum(jul),
                    total_estudantes - sum(ago),
                    total_estudantes - sum(set),
                    total_estudantes - sum(out),
                    total_estudantes - sum(nov),
                    total_estudantes - sum(dez)
                ] as inativos
            FROM (
                SELECT 
                    CASE WHEN EXTRACT(MONTH FROM data_saida) >= 1 or EXTRACT(YEAR FROM data_saida) > 2019 
                    THEN 1 ELSE 0 END AS jan,
                    CASE WHEN EXTRACT(MONTH FROM data_saida) >= 2 or EXTRACT(YEAR FROM data_saida) > 2019 
                    THEN 1 ELSE 0 END AS fev,
                    CASE WHEN EXTRACT(MONTH FROM data_saida) >= 3 or EXTRACT(YEAR FROM data_saida) > 2019
                    THEN 1 ELSE 0 END AS mar,
                    CASE WHEN EXTRACT(MONTH FROM data_saida) >= 4 or EXTRACT(YEAR FROM data_saida) > 2019
                    THEN 1 ELSE 0 END AS abr,
                    CASE WHEN EXTRACT(MONTH FROM data_saida) >= 5 or EXTRACT(YEAR FROM data_saida) > 2019
                    THEN 1 ELSE 0 END AS mai,
                    CASE WHEN EXTRACT(MONTH FROM data_saida) >= 6 or EXTRACT(YEAR FROM data_saida) > 2019
                    THEN 1 ELSE 0 END AS jun,
                    CASE WHEN EXTRACT(MONTH FROM data_saida) >= 7 or EXTRACT(YEAR FROM data_saida) > 2019
                    THEN 1 ELSE 0 END AS jul,
                    CASE WHEN EXTRACT(MONTH FROM data_saida) >= 8 or EXTRACT(YEAR FROM data_saida) > 2019
                    THEN 1 ELSE 0 END AS ago,
                    CASE WHEN EXTRACT(MONTH FROM data_saida) >= 9 or EXTRACT(YEAR FROM data_saida) > 2019
                    THEN 1 ELSE 0 END AS set,
                    CASE WHEN EXTRACT(MONTH FROM data_saida) >= 10 or EXTRACT(YEAR FROM data_saida) > 2019
                    THEN 1 ELSE 0 END AS out,
                    CASE WHEN EXTRACT(MONTH FROM data_saida) >= 11 or EXTRACT(YEAR FROM data_saida) > 2019
                    THEN 1 ELSE 0 END AS nov,
                    CASE WHEN EXTRACT(MONTH FROM data_saida) >= 12 or EXTRACT(YEAR FROM data_saida) > 2019
                    THEN 1 ELSE 0 END AS dez,
                    (SELECT COUNT(1) FROM apresentacao.estudante) as total_estudantes
                FROM apresentacao.estudante
                WHERE data_saida > '2019-01-01'
            ) count
            GROUP BY total_estudantes           
            ";

        $query = $entityManager->getConnection()->prepare($query);

        $query->execute();
        $estudante = $query->fetch();

        foreach ($estudante as &$value) {
            $value = str_replace('{', '', $value);
            $value = str_replace('}', '', $value);
            $value = explode(',', $value);
        }

        return $estudante;
    }

    public function empresasAlunos(): array
    {
        $entityManager = $this->getEntityManager();
                // $em = $this->getDoctrine()->getManager();

        $query =
            "
            SELECT 
                count(*) filter(where data_saida > current_date) as ativo, 
                count(*) filter(where data_saida < current_date) as inativo,
                empresa
            FROM apresentacao.estudante
            group by 3
            order by 2 desc        
            ";

        $query = $entityManager->getConnection()->prepare($query);

        $query->execute();
        $empresasAlunos = $query->fetchAll();
        $series = [];

        foreach ($empresasAlunos as &$value) {
            $series[] = [
                'name' => $value['empresa'],
                'data' => [[$value['ativo'], $value['inativo']]]
            ];
        }
        // echo "<pre>";
        // print_r($series);
        // die();

        return $series;
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
            "SELECT
                CASE 
                WHEN status=true THEN 'Ativo'
                WHEN status=false THEN 'Inativo'

                END as name
                , count(nome) as y
            FROM (
                SELECT 
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
            "SELECT
                case when year BETWEEN 15 and 19 then '15 - 19'
                when year BETWEEN 19 and 23 then '19 - 23'
                when year BETWEEN 23 and 27 then '23 - 27'
                when year BETWEEN 27 and 100 then '23 - 27'
                END as name,
                sum(y)::int as y
            FROM (
                SELECT
                date_part('year',age(data_nascimento)) as year
                , count(nome) as y
                FROM (
                    SELECT 
                        c.*,
                        m.descricao as descricao_motivo 
                    FROM apresentacao.estudante c
                    LEFT JOIN apresentacao.motivo m using(id_motivo)    
                   WHERE status = true

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
