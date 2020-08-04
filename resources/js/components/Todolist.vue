<template >
    <div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">My todo list</div>

                        <div class="card-body">
                            <section id="todo-form">
                                <form >
                                    <div class="d-flex w-100 justify-content-between">
                                        <input v-model="createForm.name" type="text" class="form-control" placeholder="Enter todo">
                                        <button v-if="createForm.creating" class="btn btn-primary" type="button" disabled>
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            Creating...
                                        </button>
                                        <button v-else
                                                @click.prevent="store"
                                                type="button"
                                                class="btn btn-primary ml-3">create</button>
                                    </div>
                                </form >
                            </section>
                            <section id="todo-list" class="mt-3">
                                <ul class="list-group">
                                    <li v-if="todos.data.length > 0 && !todos.loading" v-for="todo in todos.data" :key="todo.uuid" class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            {{ todo.name }}
                                        </span>
                                        <span class="d-flex justify-content-between">
                                            <a href="#" class="text-success"><i class="fa fa-edit"></i> Edit</a > &nbsp;
                                            <a href="#" class="text-danger"><i class="fa fa-trash-o"></i> Delete</a >
                                        </span>
                                    </li>
                                    <li class="list-group-item list-group-item-warning" v-if="todos.data.length === 0 && !todos.loading">
                                        No todos found
                                    </li>
                                    <li v-if="todos.loading">
                                        <div class="text-center">
                                            <div class="spinner-border" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template >

<script >
    export default {
        name: "Todolist",
        data() {
            return {
                todos: {
                    data: [],
                    loading: false
                },
                createForm: {
                    name: undefined,
                    creating: false,
                    created: false,
                    errors: []
                }
            }
        },
        mounted() {
            this.loadtodos();
        },
        methods: {
            loadtodos() {
                this.todos.loading = true;
                axios.get("/todos-json")
                .then((response) => {
                    this.todos.data = response.data;
                })
                .catch((error) => {
                    console.log(error.message);
                })
                .finally(() => {
                    this.todos.loading = false;
                })
            },
            store() {
                this.createForm.creating = true;
                const data = {
                    name: this.createForm.name
                };
                axios.post("/todos", data)
                    .then((response) => {
                        console.log(response.data);
                        this.createForm.created = true;
                        this.createForm.name = undefined;
                        this.loadtodos();
                    })
                    .catch((error) => {
                        console.log(error.message);
                    })
                    .finally(() => {
                        this.createForm.creating = false;
                    })
            }
        }
    }
</script >

<style scoped >

</style >
