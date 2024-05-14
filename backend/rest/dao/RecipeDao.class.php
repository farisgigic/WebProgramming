<?php
require_once __DIR__ . '/BaseDao.class.php';

class RecipeDao extends BaseDao
{

    public function __construct()
    {
        parent::__construct("recipes");
    }

    public function add_recipe($recipe)
    {
        return $this->insert("recipes", $recipe);
    }
    public function count_recipes_paginated($search)
    {
        $query = "SELECT COUNT(*) AS count
                  FROM recipes 
                  WHERE LOWER(name) LIKE CONCAT('%', :search, '%') OR
                        LOWER(time_taken) LIKE CONCAT('%', :search, '%') OR
                        LOWER(category) LIKE CONCAT('%', :search, '%'); 
                  ";
        // $stmt = $this->connection->prepare($query);
        // $stmt->execute(['search' => $search]);
        // $rows  = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // return reset($rows);
        
        return $this->query_unique($query, [
            "search" => $search
        ]);
    }



    public function get_recipes_paginated($offset, $limit, $search, $order_column, $order_direction)
    {
        $query = "SELECT *
                    FROM recipes
                    WHERE   LOWER(name) LIKE CONCAT('%', :search, '%') OR
                            LOWER(time_taken) LIKE CONCAT('%', :search, '%') OR
                            LOWER(category) LIKE CONCAT('%', :search, '%')
                    ORDER BY {$order_column} {$order_direction}
                    LIMIT {$offset}, {$limit}; 
                    ";

        // $stmt = $this->connection->prepare($query);
        // $stmt->execute(['search' => $search]);
        // return $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $this->query($query, [
            'search' => $search
        ]);

    }
    public function delete_recipe($id)
    {
        $query = "DELETE FROM recipes WHERE id = :id";
        $this->execute($query, ["id" => $id]);
    }
}
