<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
            <!-- Bootstrap CSS -->
            <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

            <!-- jQuery  -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


            <!-- Popper.js (Bootstrap JS) -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">


            <!-- Bootstrap JS -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
                
            <link rel="stylesheet" type="text/css" href="index.css">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <h2 class="text-center">Plateforme de gestion des fichiers</h2>
                    <div class="card">
                        <div class="card-body">
                            <form id="login" method="post">
                                <div class="form-group">
                                    <label for="Uname"><b>Login</b></label>
                                    <input type="text" class="form-control" name="Uname" id="Uname" placeholder="Entrer le nom de l'utilisateur">
                                </div>
                                <div class="form-group">
                                    <label for="Pass"><b>Mot de passe</b></label>
                                    <input type="password" class="form-control" name="Pass" id="Pass" placeholder="Entrer votre mot de pass">
                                </div>
                                <input type="submit" class="btnlogin" name="log" id="log" value="Se connecter">
                                <br><br>
                                
                                    Oublier votre mot de passe? réinitialiser votre mot de passe <button type="button" id="resetLink" class="btn btn-primary">cliquer ici</button>
                                    <br><br>
                                    Nouveau dans cette plateforme? Créer votre compte <button type="button" id="newcompt" class="btn btn-secondary">cliquer ici</button>
                               
                    </div>
                </div>
            </div>
        </div>

    <!--fenêtre de réinitialisation de mdp-->
        <div class="modal fade" id="resetPasswordModal" tabindex="-1" role="dialog" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetPasswordModalLabel">Réinitialiser votre mot de passe</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="resetPasswordForm">
                <div class="form-group">
                    <label for="resetUsername">Nom d'utilisateur</label>
                    <input type="text" class="form-control" id="resetUsername" placeholder="Entrer le nom de l'utilisateur">
                </div>
                <div class="form-group">
                    <label for="resetNewPassword">Nouveau mot de passe</label>
                    <input type="password" class="form-control" id="resetNewPassword" placeholder="Entrer votre nouveau mot de passe">
                </div>

                <button type="button" class="btn btn-primary" id="resetPasswordSubmitBtn">Soumettre</button>

                </form>
            </div>
            </div>
        </div>
        </div>

    <!-- création de la fenêtre de créer un nouveau compte -->
    <div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserModalLabel">Créer un compte</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createUserForm">
                        <div class="form-group">
                            <label for="newUsername">Nom d'utilisateur</label>
                            <input type="text" class="form-control" id="newUsername" placeholder="Entrer le nom de l'utilisateur">
                        </div>
                        <div class="form-group">
                            <label for="newPassword">Mot de passe</label>
                            <input type="password" class="form-control" id="newPassword" placeholder="Entrer votre mot de passe">
                        </div>
                        <button type="submit" class="btn btn-primary">Créer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>




    
    <script>
        //##########################################
        //###############login####################
        //##########################################
        $(document).ready(function () {
            $("#login").submit(function (event) {
                event.preventDefault();

                // obtenir les données de form
                const username = $("#Uname").val();
                const password = $("#Pass").val();
                console.log(username);
                console.log(password);
                // envoyer la demande de connecter
                $.ajax({
                url: "login.php",
                method: "POST",
                data: {
                    username: username,
                    password: password,
                },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                    //  si l'utilisateur se connect avec succès, passer à la page dossier.php
                    window.location.href = "dossier.php?username=" + response.username;
                    } else {
                    // si l'utilisateur n'arrive pas à se connecter, fait une alerte
                    alert("Nom d'utilisateur ou mot de passe incorrect.");
                    }
                },
                error: function () {
                    alert("Une erreur s'est produite lors de la connexion.");
                },
                });
            });
            });







        //##########################################
        //############### réinitialisation de mdp ####################
        //##########################################
        $(document).ready(function () {
            // apparition de la fenêtre de réinitialiser de mdp
            $("#resetLink").click(function () {
                $("#resetPasswordModal").modal("show");
            });
        });

        $("#resetPasswordSubmitBtn").click(function () {
                // obtenir les données de form
                const username = $("#resetUsername").val();
                const newPassword = $("#resetNewPassword").val();

                // envoyer la demande à  reset_password.php
                $.ajax({
                    url: "reset_password.php",
                    method: "POST",
                    data: {
                        username: username,
                        new_password: newPassword,
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            $("#resetPasswordModal").modal("hide");
                            alert("Mot de passe modifié avec succès.");
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function () {
                        alert("Une erreur s'est produite lors de la réinitialisation du mot de passe.");
                    },
                });
            });
        //##########################################
        //################ créer un nouveau compte ######################
        //##########################################
        // apparition de la fenêtre de créer un nouveau compte
        $("#newcompt").click(function () {
            $("#createUserModal").modal("show");
        });

        // création de dépôt du form 
        $("#createUserForm").submit(function (event) {
            event.preventDefault();

            // obtenir les données de form
            const newUsername = $("#newUsername").val();
            const newPassword = $("#newPassword").val();

            // envoyer la demande à create_user.php
            $.ajax({
                url: "create_user.php",
                method: "POST",
                data: {
                    username: newUsername,
                    password: newPassword,
                },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        $("#createUserModal").modal("hide");
                        alert("Compte créé avec succès!");
                    } else {
                        alert(response.message);
                    }
                },
                error: function () {
                    alert("Une erreur s'est produite lors de la création du compte.");
                },
            });
        });
    </script>
</body>
</html>
