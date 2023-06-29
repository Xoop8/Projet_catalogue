<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Styles pour le toast */
        .toast {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            z-index: 9999;
        }

        .toast.show {
            display: block;
            animation: fade-in 0.5s ease-in-out;
        }

        .toast a {
            color: white;
            text-decoration: underline;
        }
        
        .toast button {
            margin-left: 10px;
        }

        @keyframes fade-in {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <!-- Contenu de la page -->

    <!-- Toast -->
    <div class="toast" id="newsletter-toast">
        <span>Inscrivez-vous à notre newsletter pour recevoir les dernières nouvelles et offres spéciales par e-mail. <a href="inscription-newsletter.html">S'inscrire</a></span>
        <button onclick="hideToast()">Fermer</button>
    </div>

    <!-- Script pour afficher/masquer le toast -->
    <script>
        function showToast() {
            var toast = document.getElementById('newsletter-toast');
            toast.classList.add('show');
        }

        function hideToast() {
            var toast = document.getElementById('newsletter-toast');
            toast.classList.remove('show');
        }

        // Afficher le toast après un certain délai (par exemple, 5 secondes)
        setTimeout(showToast, 5000);

        function hideToast() {
    var toast = document.getElementById('newsletter-toast');
    toast.classList.remove('show');
}

// Ajoutez le code ci-dessous pour écouter les clics en dehors du toast et masquer le toast lorsque cela se produit
window.addEventListener('click', function(event) {
    var toast = document.getElementById('newsletter-toast');
    if (event.target !== toast && !toast.contains(event.target)) {
        hideToast();
    }
});

// Afficher le toast après un certain délai (par exemple, 5 secondes)
setTimeout(showToast, 5000);

    </script>
</body>

</html>
