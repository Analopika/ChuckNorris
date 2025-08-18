(function() {
    'use-strict';
})();

class Login {
    constructor() {


        if(document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', this.initialize.bind(this));
        }
        else {
            this.initialize();
        }
    }

    initialize() {
        const loginForm = document.getElementById('loginForm');
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.submit(loginForm);
        })
    }
    
    async submit(e) {
        let dto = {};
        let additionalData = [];

        let data = getFormData(e, dto, additionalData)
        // data = JSON.stringify(data);
        let request = await fetch(`http://localhost:3000/api/v1/login`, {
            method: 'POST',
            headers: {
                'Content-type': 'application/json'
            },
            body: data
        })

        let response = await request.json();
        if(response.status == 401 || response.status == 400){
            alert(response.error);
            return;
        }

        let now = Date.now()

        let expires = new Date(now + response.expires_in * 1000);


        localStorage.setItem('user_id', response.user_id);
        localStorage.setItem('access_token', response.token);
        localStorage.setItem('refresh_token', response.refresh_token);
        localStorage.setItem('expires_in', response.expires_in);
        localStorage.setItem('expires_at', expires);

        window.location = "/home";
    }
}

const login = new Login();