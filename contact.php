<?php
// Configuration
$destinataire = "olivier.marle.info@gmail.com"; // REMPLACE PAR TON EMAIL
$sujet_email = "Nouveau message depuis le portfolio";

// Vérifier que le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Récupération et nettoyage des données
    $nom = isset($_POST['nom']) ? strip_tags(trim($_POST['nom'])) : '';
    $prenom = isset($_POST['prenom']) ? strip_tags(trim($_POST['prenom'])) : '';
    $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
    $telephone = isset($_POST['telephone']) ? strip_tags(trim($_POST['telephone'])) : '';
    $sujet = isset($_POST['sujet']) ? strip_tags(trim($_POST['sujet'])) : '';
    $message = isset($_POST['message']) ? strip_tags(trim($_POST['message'])) : '';
    
    // Validation
    $erreurs = [];
    
    if (empty($nom)) {
        $erreurs[] = "Le nom est requis.";
    }
    
    if (empty($prenom)) {
        $erreurs[] = "Le prénom est requis.";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "Une adresse email valide est requise.";
    }
    
    if (empty($sujet)) {
        $erreurs[] = "Le sujet est requis.";
    }
    
    if (empty($message)) {
        $erreurs[] = "Le message est requis.";
    }
    
    // Si pas d'erreurs, envoyer l'email
    if (empty($erreurs)) {
        
        // Construction du message
        $contenu_email = "Nouveau message de contact\n\n";
        $contenu_email .= "Nom: $nom\n";
        $contenu_email .= "Prénom: $prenom\n";
        $contenu_email .= "Email: $email\n";
        $contenu_email .= "Téléphone: " . ($telephone ? $telephone : "Non renseigné") . "\n";
        $contenu_email .= "Sujet: $sujet\n\n";
        $contenu_email .= "Message:\n$message\n";
        
        // En-têtes de l'email
        $headers = "From: $prenom $nom <$email>\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        // Envoi de l'email
        if (mail($destinataire, $sujet_email, $contenu_email, $headers)) {
            // Succès - Redirection avec message
            header("Location: index.html?success=1");
            exit;
        } else {
            // Erreur d'envoi
            header("Location: index.html?error=envoi");
            exit;
        }
        
    } else {
        // Erreurs de validation - Redirection avec message d'erreur
        header("Location: index.html?error=validation");
        exit;
    }
    
} else {
    // Si accès direct au fichier PHP, rediriger vers l'accueil
    header("Location: index.html");
    exit;
}
?>
