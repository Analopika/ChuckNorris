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

        this.jokes = this.getData(`/api/v1/joke/${this.user_id}`).then(response =>{
            this.populateJokes(response);
        }
        );
    }

    async populateJokes(data) {
        let jokes = await data;
        jokes = jokes.data;
        jokes = jokes.filter(j => j.favourite == 1);
        jokes.forEach(joke => {
            $('#jokes').append(`
                <li class="pb-3">${joke.text}</li>
                `)
        });
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
                else if(response.status == 401){
                    window.location = "/"
                }
                else{
                    toastr.error("Oops. Something Went Wrong!")
                }
            })
        })
        .catch(error => {
            toastr.error(error);
        })
    }
}

const favourite = new Favourite();