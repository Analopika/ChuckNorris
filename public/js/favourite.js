(function() {
    'use-strict';
})();

class Favourite {
    constructor() {
        this.jokes = [];
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

        this.jokes = this.getData(`http://localhost:3000/api/v1/joke/${this.user_id}`).then(response =>{
            this.populateJokes(response);
        }
        );
    }

    async populateJokes(data) {
        let jokes = await data;
        jokes = jokes.data;
         $('#jokes').append(`<ul>`)
        jokes.forEach(joke => {
            $('#jokes').append(`
                <li>${joke.text}</li>
                `)
        });
        $('#jokes').append(`</ul>`)
    }


    getData(url) {
        return fetch(url, {
            method: 'GET',
            headers: {
                "Content-type": "application/json",
                'Authorization': `Bearer ${this.token}`
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
}

const favourite = new Favourite();