(function() {
    'use-strict';
})();

class Register {
    constructor() {


        if(document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', this.initialize.bind(this));
        }
        else {
            this.initialize();
        }
    }

    initialize() {
        const registerForm = document.getElementById('registerForm');
        registerForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.submit(registerForm);
        })
    }
    
    async submit(e) {
        let dto = {};
        let additionalData = [];

        let data = getFormData(e, dto, additionalData)

        let request = await fetch(`/api/v1/register`, {
            method: 'POST',
            headers: {
                'Content-type': 'application/json'
            },
            body: data
        })

        let response = await request.json();

        if(response.status == 401 || response.status == 400 || response.status == 500){
            toastr.error(response.error);
            return;
        }

        window.location = "/";
    }
}

const register = new Register();