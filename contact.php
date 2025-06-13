<section id="contact" class="contact">
    <div class="titre noir">
        <h2 class="titre-text"><span>C</span>bontact</h2>
        <p>Nous sommes à votre écoute. N'hésitez pas à nous contacter.</p>
    </div>
    <div class="contactform">
        <h3>Envoyer un message</h3>
        <form action="send_message.php" method="post">
            <div class="inputboite">
                <input type="text" name="name" placeholder="Nom" required>
            </div>
            <div class="inputboite">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="inputboite">
                <textarea name="message" placeholder="Message" required></textarea>
            </div>
            <div class="inputboite">
                <input type="submit" value="Envoyer">
            </div>
        </form>
    </div>
</section>