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
                        <table class="table table-striped table-responsive-sm">
                            <th>#</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Image</th>
                            <th>Action</th>
                            <tbody>
                            <tr v-for="(list,index) in posts">
                                <td>{{index + 1}}</td>
                                <td>{{list.get_category['category']}}</td>
                                <td>{{list.created_at}}</td>
                                <td>
                                    <button type="button" class="btn btn-success"
                                            @click="addimage(list['get_category']['category'],list.id)"><i
                                        class="fa fa-image"></i></button>

                                    <button type="button" class="btn btn-primary"
                                            @click="imageview(list['get_category']['category'],list['getimages'])"><i
                                        class="fa fa-file-image-o"></i></button>
                                </td>
                                <td>
                                    <button type="button" v-if="list.status != 0 " @click="changeStatus(list.id)"
                                            class="btn btn-outline-success"><i
                                        class="fa fa-check"></i></button>
                                    <button type="button" v-else @click="changeStatus(list.id)"
                                            class="btn btn-outline-danger"><i
                                        class="fa fa-ban"></i></button>
                                    <button type="button" class="btn btn-danger" @click="destroy(list.id)"><i
                                        class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr v-show="!posts.length">
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
                            @paginate="getPost()"
                        ></pagination>
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
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="category">Category</label>
                                        <select name="category" id="" class="form-control"
                                                :class="{ 'is-invalid': form.errors.has('category') }"
                                                v-model="form.category">
                                            <option :value="0">Select Category</option>
                                            <option v-for="list in categories" :value="list.id">{{list.category}}
                                            </option>
                                        </select>
                                        <has-error :form="form" field="category"></has-error>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create Post</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!--Image View Modal-->
        <div class="modal fade" id="modal-image-view">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{imagemodaltitle}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-4 mt-2" v-for="(list,index) in listimage">
                                <img :src="'post/'+list.name" alt="image" class="card-img"/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- Upload Image Modal-->
        <div class="modal fade" id="modal-upload-image">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{imagemodaltitle}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <vue-dropzone ref="myVueDropzone" id="dropzone" :options="dropzoneOptions"
                                      v-on:vdropzone-sending="sendingEvent"
                                      v-on:vdropzone-success="uploadSuccess"></vue-dropzone>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <vue-snotify></vue-snotify>
    </div>
</template>

<script>
    import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'

    export default {
        components: {
            vueDropzone: vue2Dropzone
        },
        data() {
            return {
                ediMode: false,
                posts: [],
                imagemodaltitle: '',
                listimage: [],
                categories: [],
                form: new Form({
                    id: '',
                    category: '0',

                }), pagination: {
                    current_page: 1
                }, dropzoneOptions: {
                    url: 'images',
                    thumbnailWidth: 150,
                    thumbnailHeight: 150,
                    maxFilesize: 5000,
                    acceptedFiles: ".jpeg,.jpg,.png,.gif",
                    headers: {
                        "X-CSRF-TOKEN": document.head.querySelector("[name=csrf-token]").content
                    }
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
                this.$snotify.success("Post List Refresh Successfully..!", "Success")
            }, store: function () {
                var self = this
                this.form.busy = true;
                this.form.post('posts').then(({data}) => {
                    if (!data.error) {
                        this.getPost()
                        self.form.reset()
                        self.form.clear()
                        $("#modal-post").modal('hide');
                        this.$snotify.success("Post Create Successfully..!", "Success")
                    }
                }).catch(e => {
                    self.$snotify.error(e, "error")
                })
            },destroy: function (id) {
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
                                    axios.delete("posts/" + id).then(response => {
                                        this.getPost();
                                        this.$snotify.success(
                                            "Post Successfully Deleted",
                                            "Success"
                                        );
                                    }).catch(e => {
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
            }, getPost: function () {
                var self = this
                axios.get("posts?page=" + this.pagination.current_page).then(function (response) {
                    self.posts = response.data.post.data;
                    self.categories = response.data.category
                    self.pagination = response.data.post;
                }).catch(e => {
                    this.$snotify.error(e, "error")
                })
            }, imageview(title, lists) {
                this.imagemodaltitle = title
                this.listimage = lists;
                $('#modal-image-view').modal('show');
                //console.log(lists);
            }, addimage: function (title, id) {
                this.imagemodaltitle = title
                this.form.id = id
                $('#modal-upload-image').modal('show')
            }, sendingEvent(file, xhr, formData) {
                formData.append('id', this.form.id);
                formData.append('type', 'post');
            }, uploadSuccess: function (file, response) {
                this.getPost()
                this.$snotify.success("Upload Image Successfully..!", "success")
            }, changeStatus: function (id) {
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
                                    axios.get("status/posts/" + id).then(response => {
                                        this.getPost();
                                        this.$snotify.success(
                                            "Post Status Changed Successfully.",
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
