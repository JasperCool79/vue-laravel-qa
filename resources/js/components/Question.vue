<template>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <form class="card-body" v-if="editing" @submit.prevent="update">
                    <div class="card-title">
                        <input type="text" class="form-control form-control-lg" v-model="title">
                    </div>
                    <div class="media">
                        <div class="media-body">
                            <div class="form-group">
                                <textarea class="form-control" rows="10" v-model="body"></textarea>
                            </div>
                            <button class="btn btn-primary" :disabled="isInvalid">Update </button>
                            <button @click.prevent="cancle" class="btn btn-secondary">Cancle </button>
                        </div>
                    </div>                    
                </form>
                <div class="card-body" v-else>
                    <div class="card-title">
                        <div class="d-flex align-items-center">
                            <h1>{{title}}</h1>
                            <div class="ml-auto">
                                <a href="/questions" class="btn btn-outline-secondary">
                                    Back To All Question
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="media">
                        <vote :model="question" name="question"></vote>
                        <div class="media-body">
                            <div v-html="body"></div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="ml-auto">
                                        <a v-if="authorize('modify',question)" @click.prevent="edit" class="btn btn-outline-info">
                                            Edit
                                        </a>
                                        <button v-if="authorize('deleteQuestion',question)" class="btn btn-outline-danger" @click="destroy">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                                <div class="col-4"></div>
                                <div class="col-4">
                                    <user-info :model="question" label="Asked"></user-info>
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['question'],
        data(){
            return{
                title: this.question.title,
                body: this.question.body,
                editing: false,
                id: this.question.id,
                beforeEditCache: {}
            }
        },
        computed: {
            isInvalid(){
                return this.body.length < 10 || this.title.length < 10;
            },
            endpoint(){
                return `/questions/${this.id}/`;
            }
        },
        methods: {
            edit(){
                this.beforeEditCache ={
                    body: this.body,
                    title: this.title
                }
                this.editing = true;
            },
            cancle(){
                this.body = this.beforeEditCache.body;
                this.title = this.beforeEditCache.title;
                this.editing = false;
            },
            update(){
                axios.put(this.endpoint,{
                    body: this.body,
                    title: this.title
                })
                .catch(({response}) => {
                    this.$toast.error(response.data.message,'Error',{timeout: 3000});
                })
                .then(({data}) => {
                    this.body = data.body;
                    this.$toast.success(data.message,'Success',{timeout: 3000});
                    this.editing = false;
                })
            },
            destroy(){
                this.$toast.question('Are you sure to delete','Confirm',{
                    timeout: 1000,
                    close: false,
                    overlay: true,
                    displayMode: 'once',
                    id: 'question',
                    zindex: 999,
                    position: 'center',
                    buttons: [
                        ['<button><b>YES</b></button>', (instance, toast) => {
                
                            axios.delete(this.endpoint)
                                .then(({data}) => {
                                    this.$toast.success(daa.message,'Success',{timeout: 2000});
                                });
                                setTimeout(()=>{
                                    window.location.href = "/questions"
                                },3000);
                
                        }, true],
                        ['<button>NO</button>', function (instance, toast) {
                
                            instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                
                        }],
                    ],
                    onClosing: function(instance, toast, closedBy){
                        console.info('Closing | closedBy: ' + closedBy);
                    },
                    onClosed: function(instance, toast, closedBy){
                        console.info('Closed | closedBy: ' + closedBy);
                    }
                });
            }
        }
        
    }
</script>

<style lang="scss" scoped>

</style>