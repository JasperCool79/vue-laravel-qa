<template>
    <div class="media post">
        <vote :model="answer" name="answer"></vote>
        <div class="media-body">
            <form v-if="editing" @submit.prevent="update">
                <div class="form-group">
                    <textarea class="form-control" rows="10" v-model="body"></textarea>
                </div>
                <button class="btn btn-primary" :disabled="inInvalid">Update </button>
                <button @click.prevent="cancle" class="btn btn-secondary">Cancle </button>
            </form>
            <div v-else>
                <div v-html="body"></div>
                <div class="row">
                    <div class="col-4">
                        <div class="ml-auto">
                            <a v-if="authorize('modify',answer)" @click.prevent="edit" class="btn btn-outline-info">
                                Edit
                            </a>
                            <button v-if="authorize('modify',answer)" class="btn btn-outline-danger" @click="destroy">
                                Delete
                            </button>
                        </div>
                    </div>
                    <div class="col-4">
        
                    </div>
                    <div class="col-4">
                        <user-info :model="answer" label="Answered"></user-info>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['answer'],
        data(){
            return {
                editing: false,
                body: this.answer.body,
                id: this.answer.id,
                questionId: this.answer.question_id,
                beforeEditCache: null,
            }
        },
        methods: {
            edit(){
                this.beforeEditCache = this.body,
                this.editing = true
            },
            cancle(){
                this.body = this.beforeEditCache,
                this.editing = false
            },
            update(){
                axios.patch(this.endpoint,{
                    body: this.body,
                }).then(res => {
                    this.editing = false
                    this.body = res.data.body
                    this.$toast.success(res.data.message,'success',{timeout: 3000})
                }).catch(err => {
                    this.$toast.error(err.response.data.message,'Error',{timeout: 3000})
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
                                .then(res => {
                                    this.$emit('deleted');
                                })
                
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
        },
        computed: {
            inInvalid(){
                return this.body.length < 10;
            },
            endpoint(){
                return `/questions/${this.questionId}/answers/${this.id}`;
            }
        }
    }
</script>

<style scoped>

</style>