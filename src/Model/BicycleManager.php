<?php

namespace App\Model;

class BicycleManager extends AbstractManager
{
    public const TABLE = 'bike';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectAllWithCategories()
    {
        return $this->pdo->query('SELECT bike.name, bike.id, category.name as model FROM '
            . CategoryManager::TABLE . ' JOIN ' . self::TABLE . ' ON category.id=bike.category_id ')->fetchAll();
    }

    public function selectByCategory(int $category): array
    {
        // prepared request
        $statement = $this->pdo->prepare(
            'SELECT b.id, b.name, b.image, c.name as model FROM ' . self::TABLE . ' b ' .
            'JOIN category c ON b.category_id=c.id WHERE c.id=:category ' .
            'ORDER BY b.name'
        );
        $statement->bindValue('category', $category, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }
}
