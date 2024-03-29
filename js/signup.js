const form = document.querySelector("form");

const name = document.getElementById("inputName");

const lastname = document.getElementById("inputLastname");

const email = document.querySelector("#inputEmail");

const password = document.getElementById("inputPassword");

const alertPlaceholder = document.getElementById('liveAlertPlaceholder');

const appendAlert = (message, type) => {
    const wrapper = document.createElement('div')
    wrapper.innerHTML = [
      `<div class="alert alert-${type} alert-dismissible" role="alert">`,
      `   <div>${message}</div>`,
      '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
      '</div>'
    ].join('')
  
    alertPlaceholder.append(wrapper)
}

form.addEventListener("submit", ev => {
    ev.preventDefault();
    fetch("https://fad.fantagita.site/api/signup", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
			name: name.value,
			lastname: lastname.value,
            email: email.value,
            password: password.value
        })
    })
    .then(res => res.json())
    .then(json => {
        let typeAlert = "danger";
        if (json["success"])
            typeAlert = "success";
        if (json["message"] !== undefined)
            appendAlert(json["message"], typeAlert);
        
    })
    .catch(console.error);
});
