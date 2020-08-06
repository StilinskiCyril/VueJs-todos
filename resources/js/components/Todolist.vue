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
                                        <button
                                            v-on:keyup.enter="store"
                                            v-else
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
                                            <a @click.prevent="showEditTodoModal(todo)" href="#" class="text-success"><i class="fa fa-edit"></i> Edit</a > &nbsp;
                                            <a @click.prevent="destroy(todo)" href="#" class="text-danger"><i class="fa fa-trash-o"></i> Delete</a >
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

        <!-- Modal -->
        <div class="modal fade" id="editTodoModal" tabindex="-1" role="dialog" aria-labelledby="editTodoModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTodoModalLabel">Edit Todo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form >
                            <div class="d-flex w-100 justify-content-between">
                                <input v-model="editForm.name" type="text" class="form-control" placeholder="Enter todo">
                                <button v-if="editForm.updating" class="btn btn-primary" type="button" disabled>
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Updating...
                                </button>
                                <button
                                    v-on:keyup.enter="update"
                                    v-else
                                    @click.prevent="update"
                                    type="button"
                                    class="btn btn-primary ml-3">edit</button>
                            </div>
                        </form >
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template >

<script >
import Swal from 'sweetalert2/dist/sweetalert2.js'
import 'sweetalert2/src/sweetalert2.scss'

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
                    creating: false
                },
                editForm: {
                    name: undefined,
                    updating: false
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
                        this.createForm.name = undefined;
                        Swal.fire({
                            icon: 'success',
                            title: 'Created',
                            text: 'Todo item created successfully'
                        })
                        this.loadtodos();
                    })
                    .catch((error) => {
                        if (error.response && error.response.status === 422){
                            Swal.fire({
                                icon: 'error',
                                title: error.response.data.message,
                                text: _.flatten(_.toArray(error.response.data.errors))
                            })
                        }
                        console.log(error.message);
                    })
                    .finally(() => {
                        this.createForm.creating = false;
                    })
            },
            update() {
                this.editForm.updating = true;
                const data = {
                    name: this.editForm.name
                };
                axios.put(`/todos/${this.editForm.uuid}`, data)
                    .then((response) => {
                        console.log(response.data);
                        this.editForm.updated = false;
                        this.editForm.uuid = undefined;
                        this.editForm.name = undefined;
                        $('#editTodoModal').modal('hide');
                        this.loadtodos();
                    })
                    .catch((error) => {
                        console.log(error.message);
                    })
                    .finally(() => {
                        this.editForm.updating = false;
                    })
            },
            destroy(todo) {
                axios.delete(`/todos/${todo.uuid}`)
                .then((response) => {
                    this.loadtodos();
                })
            },
            showEditTodoModal(todo) {
                this.editForm.uuid = todo.uuid;
                this.editForm.name = todo.name;
                $('#editTodoModal').modal('show');
            }
        }
    }
</script >

<style scoped >

</style >
