<style>
    .baniere {
        position: relative;
        width: 100%;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background-image: url('image1.jpeg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        overflow: hidden;
    }

    .baniere::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.3) 100%);
        z-index: 1;
    }

    .baniere .contenu {
        max-width: 800px;
        text-align: center;
        position: relative;
        z-index: 2;
        padding: 40px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.1);
        animation: fadeIn 1s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .baniere .contenu h1 {
        color: #fff;
        font-size: 4em;
        font-weight: 700;
        margin-bottom: 20px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        letter-spacing: 2px;
    }

    .baniere .contenu .highlight {
        color: #fb911f;
        position: relative;
        display: inline-block;
    }

    .baniere .contenu .highlight::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 100%;
        height: 3px;
        background-color: #fb911f;
        transition: width 0.3s ease;
    }

    .baniere .contenu .highlight:hover::after {
        width: 0;
    }

    .baniere .contenu h2 {
        color: #fff;
        font-size: 2.5em;
        text-transform: capitalize;
        margin-bottom: 20px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    .baniere .contenu p {
        color: #fff;
        font-size: 1.2em;
        margin-bottom: 30px;
        line-height: 1.8;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .cta-buttons {
        display: flex;
        justify-content: center;
        gap: 20px;
    }

    .btn {
        font-size: 1.1em;
        color: #fff;
        padding: 15px 30px;
        display: inline-block;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 2px;
        transition: all 0.3s ease;
        border-radius: 50px;
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: all 0.5s;
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn1 {
        background: #fb911f;
    }

    .btn2 {
        background: #2A4963;
    }

    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .scroll-indicator {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        text-align: center;
        color: #fff;
        z-index: 2;
        animation: bounce 2s infinite;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .scroll-indicator:hover {
        transform: translateX(-50%) scale(1.1);
    }

    .scroll-indicator .icon {
        font-size: 2em;
        display: block;
        margin-bottom: 5px;
    }

    .scroll-indicator .text {
        font-size: 0.9em;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0) translateX(-50%); }
        40% { transform: translateY(-30px) translateX(-50%); }
        60% { transform: translateY(-15px) translateX(-50%); }
    }

    @media (max-width: 768px) {
        .baniere .contenu h1 {
            font-size: 2.5em;
        }
        .baniere .contenu h2 {
            font-size: 2em;
        }
        .baniere .contenu p {
            font-size: 1em;
        }
        .cta-buttons {
            flex-direction: column;
            align-items: center;
        }
        .btn {
            width: 80%;
            margin-bottom: 15px;
        }
    }
</style>

<section id="baniere" class="baniere">
    <div class="contenu">
        <h1>Bienvenue chez <span class="highlight">HS FOOD</span></h1>
        <h2>Que des plats délicieux</h2>
        <p>Découvrez notre cuisine raffinée et nos saveurs uniques dans une ambiance chaleureuse. Chaque plat est une expérience gustative inoubliable.</p>
        <div class="cta-buttons">
            <a href="index.php?page=menu" class="btn btn1">Découvrir notre menu</a>
            <a href="index.php?page=reservation" class="btn btn2">Réserver une table</a>
        </div>
    </div>
    <div class="scroll-indicator" onclick="scrollToNextSection()">
        <span class="icon">&#9660;</span>
        <span class="text">Découvrez plus</span>
    </div>
</section>

<script>
    function scrollToNextSection() {
        const nextSection = document.querySelector('#baniere').nextElementSibling;
        if (nextSection) {
            nextSection.scrollIntoView({ behavior: 'smooth' });
        }
    }
</script>