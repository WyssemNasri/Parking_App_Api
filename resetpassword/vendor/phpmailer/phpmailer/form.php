<!DOCTYPE html>
<html>
<head>
    <title>Nouveau mot de passe - ParkiTn</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 350px;
            padding: 30px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
            font-size: 28px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-size: 22px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease-in-out;
        }

        input[type="password"]:focus {
            outline: none;
            border-color: #4CAF50;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease-in-out;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Bienvenue sur ParkiTN 📱</h1>
        <form action="trait.php" method="get">
            <h2>Réinitialisation du mot de passe</h2>
            <label for="cle">Votre clé :</label>
            <input type="password" id="cle" name="cle" placeholder="Entrez votre clé" required>

            <label for="password">Nouveau mot de passe :</label>
            <input type="password" id="password" name="password" placeholder="Entrez votre nouveau mot de passe" required>

            <input type="submit" value="Enregistrer">
        </form>
    </div>
</body>
</html>
