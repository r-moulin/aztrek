<?php
function getAllSejours(int $limit=999) : array {
    global $connection;

    $query = "
            SELECT 
                    sejour. * ,
                    pays.nom AS pays,
                    guide.libelle AS guide,
                    difficulte.libelle AS difficulte
                
            FROM sejour
            INNER JOIN pays  on sejour.pays_id = pays.id
            INNER JOIN guide on sejour.guide_id = guide.id
            INNER JOIN difficulte on sejour.difficulte_id = difficulte.id
            GROUP BY sejour.id
            LIMIT $limit
            ";

    $stmt = $connection->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll();
}

function getOneSejours(int $id): array {
    global $connection;

    $query = "
            SELECT 
                    sejour. * , 
                    pays.libelle AS pays,
                    guide.libelle,
                    depart.prix,depart.depart,
                    image.libelle
                
            FROM sejour
            INNER JOIN pays  on sejour.pays_id = pays.id
            INNER JOIN guide  on sejour.pays_id = guide.id
            INNER JOIN image  on sejour.id = image.sejour_id  
            INNER JOIN depart  on sejour.id = depart.sejour_id
            INNER JOIN guide gt on sejour.guide_id = gt.id
            WHERE sejour.publie = 1
            AND sejour.id = :id
            GROUP BY sejour.id
            ";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();

    return $stmt->fetch();

}

function insertSejour(string $titre,string $description,int $places,int $a_la_une,int $duree,int $publie,int $guide_id,int $difficulte_id,int $pays_id) {
    global $connection;

    $query = "
    INSERT INTO sejour (titre, description, places, a_la_une, duree, publie,guide_id,difficulte_id , pays_id) 
    VALUES (:titre, :description, :places, :a_la_une, :duree, :publie,:guide, :difficulte, :pays_id)

    ";

    $stmt = $connection->prepare($query);
    $stmt->bindParam(":titre", $titre);
    $stmt->bindParam(":description", $description);
    $stmt->bindParam(":places", $places);
    $stmt->bindParam(":a_la_une", $a_la_une);
    $stmt->bindParam(":duree", $duree);
    $stmt->bindParam(":publie", $publie);
    $stmt->bindParam(":guide", $guide_id);
    $stmt->bindParam(":difficulte", $difficulte_id);
    $stmt->bindParam(":pays_id", $pays_id);
    $stmt->execute();
}