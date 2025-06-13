<style>
    body{
        padding-top: 20vh;
    }
    #reservation {
    background-color: #f9f9f9;
    padding: 40px 20px;
    max-width: 600px;
    margin: 0 auto;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    font-family: Arial, sans-serif;
}

#reservation h2 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 28px;
    color: #333;
}

/* Formulaire */
#reservation form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Boîtes de saisie */
.inputboite input[type="date"],
.inputboite input[type="time"],
.inputboite input[type="number"],
.inputboite textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 16px;
    box-sizing: border-box;
    transition: border-color 0.3s ease;
}

.inputboite input:focus,
.inputboite textarea:focus {
    border-color: #4CAF50;
    outline: none;
}

/* Bouton de soumission */
.inputboite input[type="submit"] {
    background-color:rgb(255, 162, 2);
    color: white;
    border: none;
    padding: 14px;
    font-size: 16px;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.inputboite input[type="submit"]:hover {
    background-color:rgb(255, 162, 2);
}

/* Textearea */
.inputboite textarea {
    min-height: 100px;
    resize: vertical;
}
</style>
<section id="reservation" class="reservation">
    <h2>Réservation</h2>
    <form action="reservation_process.php" method="post">
        <div class="inputboite">
            <input type="date" name="date" required>
        </div>
        <div class="inputboite">
            <input type="time" name="time" required>
        </div>
        <div class="inputboite">
            <input type="number" name="people" placeholder="Nombre de personnes" min="1" required>
        </div>
        <div class="inputboite">
            <textarea name="special_requests" placeholder="Demandes spéciales"></textarea>
        </div>
        <div class="inputboite">
            <input type="submit" value="Réserver">
        </div>
    </form>
</section>