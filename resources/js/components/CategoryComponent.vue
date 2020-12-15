<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Category
                        <span class="float-md-right">
                         <button class="btn btn-success" @click="create">
                             <i class="fa fa-plus"></i>&nbsp;&nbsp;Add Category</button>
                         <button class="btn btn-primary" @click="reload"><i
                             class="fa fa-refresh"></i>&nbsp;&nbsp;Reload</button>
                     </span>
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-2">
                                    <strong>Search By :</strong>
                                </div>
                                <div class="col-md-3">
                                    <select v-model="queryFiled" class="form-control" id="fileds">
                                        <option value="category"> Category</option>
                                    </select>
                                </div>
                                <div class="col-md-7">
                                    <input v-model="query" type="text" class="form-control" placeholder="Search">
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-responsive-sm">
                            <th>#</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Action</th>
                            <tbody>
                            <tr v-for="(list,index) in categories">
                                <td>{{index + 1}}</td>
                                <td>{{list.category}}</td>
                                <td>{{list.created_at}}</td>
                                <td>
                                    <button type="button" v-if="list.status != 0 " class="btn btn-outline-success"><i
                                        class="fa fa-check" @click="changeStatus(list.id)"></i></button>
                                    <button type="button" v-else class="btn btn-outline-danger"><i
                                        class="fa fa-ban" @click="changeStatus(list.id)"></i></button>
                                    <button type="button" class="btn btn-primary" @click="editForm(list)"><i
                                        class="fa fa-pencil"></i></button>
                                    <button type="button" class="btn btn-danger" @click="destroy(list.id)"><i
                                        class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr v-show="!categories.length">
                                <td colspan="6">
                                    <div class="alert alert-danger" role="alert">Sorry :( No data found.</div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <pagination
                            v-if="pagination.last_page > 1"
                            :pagination="pagination"
                            :offset="5"
                            @paginate="query === '' ? getCategory() : searchData()"
                        ></pagination>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-category">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{editMode ?'Edit':'ADD'}} CATEGORY</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <form @submit.prevent="editMode ?update():store()" @keydown="form.onKeydown($event)">

                        <div class="modal-body">
                            <div class="form-group">
                                <label for="category">Category</label>
                                <input type="text" class="form-control"
                                       :class="{ 'is-invalid': form.errors.has('category') }"
                                       v-model.trim="form.category"
                                       id="" placeholder="Enter Category">
                                <has-error :form="form" field="category"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">{{editMode ?'Edit':'ADD'}} Category</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <vue-snotify></vue-snotify>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                editMode: false,
                categories: [],
                queryFiled: 'category',
                query: '',
                form: new Form({
                    id: '',
                    category: ''
                }), pagination: {
                    current_page: 1
                }
            }
        },
        mounted() {
            this.editMode = false
            this.getCategory()
            console.log('Component mounted.')
        }, watch: {
            query: function (newQ, old) {
                if (newQ === "") {
                    this.getCategory();
                } else {
                    this.searchData();
                }
            }
        }, methods: {
            getCategory: function () {
                var self = this
                axios.get("categories?page=" + this.pagination.current_page).then(function (response) {
                    self.categories = response.data.data
                    self.pagination = response.data;
                }).catch(e => {
                    this.$snotify.error(e, "error")
                })
            }, searchData: function () {
                axios
                    .get(
                        "search/categories/" +
                        this.queryFiled +
                        "/" +
                        this.query +
                        "?page=" +
                        this.pagination.current_page
                    )
                    .then(response => {
                        this.categories = response.data.data;
                        self.pagination = response.data;
                    })
                    .catch(e => {
                        console.log(e);
                    });
            },
            create: function () {
                this.editMode = false
                this.form.clear()
                this.form.reset()
                $("#modal-category").modal('show')

            }, editForm: function (category) {
                this.editMode = true
                this.form.clear()
                this.form.reset()
                this.form.fill(category)
                $("#modal-category").modal('show');

            }, reload: function () {
                this.getCategory()
                this.$snotify.success("Category List Refresh", "Refresh")
            }, store: function () {
                var self = this
                this.form.busy = true;
                this.form.post('categories').then(({data}) => {
                    if (!data.error) {
                        this.getCategory()
                        self.form.reset()
                        self.form.clear()
                        $("#modal-category").modal('hide');
                        this.$snotify.success(data.message, "Success")
                    }
                }).catch(e => {
                    self.$snotify.error(e, "error")
                })
            }, update: function () {
                this.form.busy = true;
                this.form.put('categories/' + this.form.id)
                    .then(({data}) => {
                        if (!data.error) {
                            this.editMode = false
                            this.form.reset()
                            this.form.clear()
                            this.getCategory()
                            $("#modal-category").modal('hide');
                            this.$snotify.success(data.message, "Success")
                        }
                    }).catch(e => {
                    this.$snotify.error(e, "error")
                })
            }, destroy: function (id) {
                this.$snotify.clear();
                this.$snotify.confirm(
                    "You will not be able to recover this data!",
                    "Are you sure?",
                    {
                        showProgressBar: false,
                        closeOnClick: false,
                        pauseOnHover: true,
                        buttons: [
                            {
                                text: "Yes",
                                action: toast => {
                                    this.$snotify.remove(toast.id);
                                    axios.delete("categories/" + id).then(response => {
                                        this.getCategory();
                                        this.$snotify.success(
                                            "Category Successfully Deleted",
                                            "Success"
                                        );
                                    })
                                        .catch(e => {
                                            console.log(e);
                                        });
                                },
                                bold: true
                            },
                            {
                                text: "No",
                                action: toast => {
                                    this.$snotify.remove(toast.id);
                                },
                                bold: true
                            }
                        ]
                    }
                );
            },changeStatus:function (id) {
                this.$snotify.clear();
                this.$snotify.confirm(
                    "Are you sure Change Status.?",
                    {
                        showProgressBar: false,
                        closeOnClick: false,
                        pauseOnHover: true,
                        buttons: [
                            {
                                text: "Yes",
                                action: toast => {
                                    this.$snotify.remove(toast.id);
                                    axios.get("status/categories/" + id).then(response => {
                                        this.getCategory();
                                        this.$snotify.success(
                                            "Category Status Changed Successfully.",
                                            "Success"
                                        );
                                    })
                                        .catch(e => {
                                            console.log(e);
                                        });
                                },
                                bold: true
                            },
                            {
                                text: "No",
                                action: toast => {
                                    this.$snotify.remove(toast.id);
                                },
                                bold: true
                            }
                        ]
                    }
                );
            }
        }
    }
</script>
