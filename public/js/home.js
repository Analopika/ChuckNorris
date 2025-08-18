(function() {
    'use-strict';
})();

class Home {
    constructor() {
        this.categories = [];
        this.joke = {};
        this.user_id = localStorage.getItem("user_id");
        this.token = localStorage.getItem("access_token");

        if(document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', this.initialize.bind(this));
        }
        else {
            this.initialize();
        }
    }


    initialize() {

        this.categories = this.getData("https://api.chucknorris.io/jokes/categories").then(response =>{
            this.populateCategories(response);
        }
        );

        const likeButton = document.getElementById('like');
        likeButton.addEventListener('click', (e) => {
            e.preventDefault();
            this.likeJoke(this.joke);
        })

        const searchForm = document.getElementById('searchForm');
        searchForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.getJoke(searchForm);
        })
    }

    populateCategories(d) {
        $('#category').empty();
        $('#category').append('<option value="">Select A Category</option>');

        d.forEach(category => {
            $('#category').append(`<option value="${category}">${category}</option>`);
        });
    }

    async likeJoke(joke) {
        try {
            let data = {
                "joke_id": joke.id,
                "user_id": this.user_id
            }

            data = JSON.stringify(data);

            let request = await fetch('http://localhost:3000/api/v1/joke', {
                method: 'PATCH',
                headers: {
                    'Content-type': 'application/json',
                    'Authorization': `Bearer ${this.token}`
                },
                body: data
            });

            let response = await request.json();
        
            if(response.status === 200){
                alert('Joke Successfully Liked!')
            }
            else{
                alert("Oops. Something went wrong Liking the joke!");
            }
        } catch (error) {
            alert(error);
        }
        
    }

    showJoke(joke) {
        $('#joke_text').text(joke.value);
        $('#joke_icon').attr('src',joke.icon_url);

        $('#joke_card').removeClass('d-none');
    }

    getFormData(form, dto, additionalData) {
        let formDataArray = $(form).serializeArray();
        formDataArray = formDataArray.concat(additionalData);

        for (let i = 0; i < formDataArray.length; i++) {
            let field = formDataArray[i];
            dto[field.name] = field.value;
        }

        return JSON.stringify(dto);
    }

    getData(url) {
        return fetch(url, {
            method: 'GET',
            headers: {
                "Content-type": "application/json"
            }
        }).then(response => {
            return response.json().then(data => {
                if(response.status === 200){
                    return data;
                }
                else{
                    alert("Oops. Something Went Wrong!")
                }
            })
        })
        .catch(error => {
            alert(error);
        })
    }

    getJoke(e) {
        let dto = {};
        let additionalData = [];

        let data = this.getFormData(e, dto, additionalData);
        let category = JSON.parse(data);
        this.getData(`https://api.chucknorris.io/jokes/random?category=${category.category}`)
        .then(response => {
            if (response) {
                this.joke = response;
                this.showJoke(response);
                this.postJoke();
            } else {
                alert("Oops. Something went wrong!");
            }
        })
        .catch(error => {
            alert(error.message);
        });
    }

    async postJoke() {

        let data = {
            "joke_id": this.joke.id,
            "text": this.joke.value,
            "user_id": this.user_id
        }

        data = JSON.stringify(data);

        let request = await fetch(`http://localhost:3000/api/v1/joke`, {
            method: 'POST',
            headers: {
                'Content-type': 'application/json',
                'Authorization': `Bearer ${this.token}`
            },
            body: data
        })

        let response = await request.json();

        if(response.status == 401){
            window.location = "/"
        }
        else if(response.status == 400) {
            alert(response.error);
            return;
        }
        else if(response.status == 201) {
            alert(response.message);
            return;
        }
        else if(response.status == 200){
            alert("You've seen this one before!");
            return;
        }
        else {
            alert(response.error);
            return;
        }
    }
}

const home = new Home();