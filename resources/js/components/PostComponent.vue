<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Post List
                        <span class="float-md-right">
                        <button class="btn btn-success" @click="create">
                            <i class="fa fa-plus"></i>&nbsp;&nbsp;New Post
                        </button>
                        <button class="btn btn-primary" @click="reload"><i
                            class="fa fa-refresh"></i>&nbsp;&nbsp;Reload
                        </button>
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
                                        <option value="festival_name">Name</option>
                                        <option value="festival_date">Date</option>
                                    </select>
                                </div>
                                <div class="col-md-7">
                                    <input v-model="query" type="text" class="form-control" placeholder="Search">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Post Model-->

        <div class="modal fade" id="modal-post">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">New Post</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>

                    <form @submit.prevent="editMode ?update():store()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="festival">Festival Name</label>
                                        <input type="text" class="form-control"
                                               :class="{ 'is-invalid': form.errors.has('festival') }"
                                               v-model.trim="form.festival"
                                               id="" placeholder="Enter Festival">
                                        <has-error :form="form" field="category"></has-error>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Create Post</button>
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
                ediMode: false,
                posts: [],
                queryFiled: 'festival_name',
                query: '',
                form: new Form({
                    festival: '',

                }), pagination: {
                    current_page: 1
                }
            }
        },
        mounted() {
            this.getPost()
            console.log('Component mounted.')
        },
        methods: {
            create: function () {
                this.editMode = false
                $("#modal-post").modal('show')
            }, reload: function () {
                this.getPost()
            }, store: function () {

            }, update: function () {

            }, getPost: function () {
                var self = this
                axios.get("posts?page=" + this.pagination.current_page).then(function (response) {
                    self.posts = response.data;
                    self.pagination = response.data;
                }).catch(e => {
                    this.$snotify.error(e, "error")
                })
            }
        }
    }
</script>
