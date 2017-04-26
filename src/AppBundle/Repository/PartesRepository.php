<?php

namespace AppBundle\Repository;

/**
 * PartesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PartesRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Función que devuelve los partes que estan en estado Comunicado, pueden ser ordenados por fecha.
     * @param bool $sortDate
     * @return array
     */
    public function getPartesByEstado($estado = 'Comunicado', $sortDate = true)
    {
        if (!$sortDate)
            $query = $this->getEntityManager()->createQuery(
                'SELECT p 
             FROM AppBundle\Entity\Partes p
             WHERE p.idEstado IN 
             (SELECT e.id 
             FROM AppBundle\Entity\EstadosParte e
             WHERE e.estado = :estado)'
            );
        else
            $query = $this->getEntityManager()->createQuery(
                'SELECT p 
             FROM AppBundle\Entity\Partes p
             WHERE p.idEstado IN 
             (SELECT e.id   
             FROM AppBundle\Entity\EstadosParte e
             WHERE e.estado = :estado)
             ORDER BY p.fecha DESC'
            );

        $query->setParameter('estado', $estado);
        return $query->getResult();
    }

    /**
     * Función que devuelve todos los partes ordenados por fecha
     * @return array
     */
    public function getPartesOrdenados(){
        $query = $this->getEntityManager()->createQuery(
            'SELECT p 
             FROM AppBundle\Entity\Partes p
             ORDER BY p.fecha DESC'
        );

        return $query->getResult();
    }

    /**
     * Función que devuelve un parte por id.
     * @param $id
     * @return mixed
     */
    public function getParteById($id)
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select("p")
            ->from("AppBundle:Partes", "p")
            ->where("p.id = :id")
            ->setParameter("id", $id)
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * Función que devuelve los partes que contienen la cadena pasada por parámetro
     * @param $string
     * @return array
     */
    public function getPartesLike($string){
        if($string == '')
            return $this->getPartesOrdenados();
        $query = $this->getEntityManager()->createQuery(
            "SELECT p
             FROM AppBundle\Entity\Partes p
             JOIN p.idAlumno as alumno
             JOIN p.idProfesor as profesor
             JOIN p.idTipo as tipo
             JOIN p.idEstado as estado
             WHERE (p.fecha LIKE :string OR alumno.nombre LIKE :string
             OR profesor.nombre LIKE :string OR tipo.tipo LIKE :string)
             ORDER BY p.fecha DESC"
        );
        $query->setParameter("string", '%'.$string.'%');
        return $query->getResult();
    }

}
