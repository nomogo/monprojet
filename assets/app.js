document.getElementById('userForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const object = {};
    formData.forEach((value, key) => {
        if (object[key]) {
            if (!Array.isArray(object[key])) object[key] = [object[key]];
            object[key].push(value);
        } else {
            object[key] = value;
        }
    });

    fetch("webservice.php", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(object)
    })
    .then(response => response.json())
    .then(data => {
        const div = document.getElementById("resultat");
        if (data.login) {
            div.innerHTML = `<p>Votre login : <b>${data.login}</b><br>Mot de passe : <b>${data.mot_de_passe}</b><br><a href="${data.profile_url}">Voir profil</a></p>`;
        } else if (data.status === "ok") {
            div.innerHTML = "<p style='color:green;'>Profil mis à jour !</p>";
        } else {
            div.innerHTML = `<p style='color:red;'>Erreur : ${data.error || 'Inconnue'}</p>`;
        }
    });
});
