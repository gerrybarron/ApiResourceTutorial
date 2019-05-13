<template>
    <div>
        <h2>Articles</h2>
        
        <form @submit.prevent="addOrUpdateArticle()" class="mb-3">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Title" v-model="article.title">
            </div>
            <div class="form-group">
                <textarea class="form-control" placeholder="Body" v-model="article.body"></textarea>
            </div>
            <button type="submit" class="btn btn-info btn-block">Save</button>
        </form>

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li v-bind:class="[{ disabled: !pagination.prev_page_url }]" class="page-item">
                    <a class="page-link" href="#" @click="fetchArticles(pagination.prev_page_url)">Previous</a>
                </li>
                <li class="page-item disabled">
                    <a class="page-link text-dark" href="#">
                        Page {{ pagination.current_page }} of {{ pagination.last_page }}
                    </a>
                </li>
                <!-- <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li> -->
                <li v-bind:class="[{ disabled: !pagination.next_page_url }]" class="page-item">
                    <a class="page-link" href="#" @click="fetchArticles(pagination.next_page_url)">Next</a>
                </li>
            </ul>
        </nav>

        <div class="card card-body mb-3" v-for="article in articles" v-bind:key="article.id">
            <h3>{{ article.title }}</h3>
            <p>{{ article.body }}</p>
            <hr>
            <button @click="editArticle(article)" type="button" class="btn btn-primary mb-3">Edit</button>
            <button @click="deleteArticle(article.id)" type="button" class="btn btn-danger">Delete</button>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return{
            articles: [],
            article:{
                id: '',
                title: '',
                body: '',
                created_at: ''
            },
            article_id: '',
            pagination: {

            },
            edit: false
        }
    },
    created(){
        this.fetchArticles();
    },
    methods: {
        fetchArticles(page_url) {
            let vm = this;
            page_url = page_url || 'api/articles';
            // fetch('api/articles')
            fetch(page_url)
            .then(res => res.json())
            .then(res => {
                console.log(res.data);
                this.articles = res.data;
                vm.makePagination(res.meta, res.links);
            })
            .catch(err => console.log(err));
        },
        makePagination(meta, links){
            let pagination = {
                current_page: meta.current_page,
                last_page: meta.last_page,
                next_page_url: links.next,
                prev_page_url: links.prev,
                current_page_url: meta.path+'?page='+meta.current_page
            }
            // console.log(pagination.current_page_url);
            this.pagination = pagination;
        },
        deleteArticle(id){
            if(confirm('Are you sure?')){
                fetch(`api/article/${id}`, {
                    method: 'delete'
                })
                .then(res => res.json())
                .then(data => {
                    alert('Article Removed');
                    this.fetchArticles(this.pagination.current_page_url);
                })
                .catch(err => console.log(err));
            }
        },
        addOrUpdateArticle() {
            if(this.edit === false){
                fetch('api/article', {
                    method: 'post',
                    body: JSON.stringify(this.article),
                    headers: {
                        'content-type': 'application/json'
                    }
                })
                .then(res => res.json)
                .then(data => {
                    this.article.title = '';
                    this.article.body = '';
                    alert('Article Added.');
                    this.fetchArticles(this.pagination.current_page_url);
                })
                .catch(err => console.log(err));
            } else {
                fetch('api/article', {
                    method: 'put',
                    body: JSON.stringify(this.article),
                    headers: {
                        'content-type': 'application/json'
                    }
                })
                .then(res => res.json)
                .then(data => {
                    this.article.title = '';
                    this.article.body = '';
                    alert('Article Updated.');
                    this.fetchArticles(this.pagination.current_page_url);
                    this.edit = false;
                })
                .catch(err => console.log(err));
            }
        },
        editArticle(article) {
            this.edit = true;
            this.article.id = article.id;
            this.article.article_id = article.id;
            this.article.title = article.title;
            this.article.body = article.body;
        }
    }
}
</script>