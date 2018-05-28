<?php
    

    $db = new PDO('mysql:host=localhost;dbname=classicmodels', 'root', 'troiswa');
    $db->exec('SET NAMES UTF8');

    $id = $_GET['orderNumber'];

    $sql = "SELECT * FROM orders WHERE orderNumber = $id";

    $statement = $db->prepare($sql);
    $statement->execute();
    $bdc = $statement->fetch(\PDO::FETCH_ASSOC);
  
    // print_r($bdc);


    $customerId = $bdc['customerNumber'];
    $sql = "SELECT * FROM customers WHERE customerNumber = $customerId";
    
    $statement = $db->prepare($sql);
    $statement->execute();
    $customer = $statement->fetch(\PDO::FETCH_ASSOC);

   // print_r($customer);


   $orderDetails = $bdc['orderNumber'];
   $sql = "SELECT * FROM orderdetails JOIN products ON products.productCode = orderdetails.productCode WHERE orderNumber = $orderDetails";
    $statement = $db->prepare($sql);
    $statement->execute();
    $orderDetails = $statement->fetchALL(\PDO::FETCH_ASSOC);

    // print_r($orderDetails);



    $montantHt = $bdc['orderNumber'];
    $sql = "SELECT SUM(quantityOrdered * priceEach) AS HT ,SUM(quantityOrdered * priceEach)*0.2 AS ttc FROM `orderdetails` WHERE orderNumber = $montantHT ";
    $statement = $db->prepare($sql);
    $statement->execute();
    $montantHT = $statement->fetchALL(\PDO::FETCH_ASSOC);

    print_r($montantHT);

    


?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>PHP</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <section>
        <h1>Bons de commande</h1>

        <a href="index.phtml">Retourner à l'accueil</a>

     
        <article class="order-form-customer">
        
            <h2><?= $customer['customerName'] ?></h2>
            <h3><?= $customer['contactFirstName'] . " " . $customer['contactLastName']  ?></h3>
            <p><?= $customer['addressLine1'] ?></p>
            <p></p>
            <p><?= $customer['city'] ?></p>

        </article>
        <hr>

        <table class="standard-table">
            <caption><?= "Bon de commande n°" . $bdc['orderNumber']?></caption>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th class="money-column">Prix Unitaire</th>
                    <th>Quantité</th>
                    <th class="money-column">Prix Total</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th colspan="3">Montant Total HT</th>
                    <?php foreach ($montantHT as $donnees): ?>
                    <th><?= $donnees['sum(quantityOrdered)'] * $donnees['SUM(priceEach)'] ?><!-- 10,223.83 --> €</th>
                     <?php endforeach; ?> 
                </tr>
                <tr>
                    <th colspan="3">TVA (20 %)</th>
                    <th>2,044.77 €</th>
                </tr>
                <tr>
                    <th colspan="3">Montant Total TTC</th>

                    <th>12,268.60 €</th>
                </tr>
            </tfoot>
            <tbody>
             <?php foreach ($orderDetails as $donnees): ?>
                <tr>
                    <td><?= $donnees['productName'] ?></td>
                    <td class="money-column"><?= $donnees['priceEach'] ?></td>
                    <td><?= $donnees['quantityOrdered'] ?></td>
                    <td class="money-column"><?= $donnees['priceEach'] * $donnees['quantityOrdered'] ?></td>
                </tr>
                 <?php endforeach; ?>
