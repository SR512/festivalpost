<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Festival List
                        <span class="float-md-right">
                        <button class="btn btn-success" @click="create">
                            <i class="fa fa-plus"></i>&nbsp;&nbsp;New Festival
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
                        <table class="table table-striped table-responsive-sm">
                            <th>#</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Festival Info</th>
                            <th>Image</th>
                            <th>Action</th>
                            <tbody>
                            <tr v-for="(list,index) in festivals">
                                <td>{{index + 1}}</td>
                                <td>{{list.festival_name}}</td>
                                <td>{{list.festival_date}}</td>
                                <td>{{list.festival_info}}</td>
                                <td>
                                    <button type="button" class="btn btn-success"
                                            @click="addimage(list.festival_name,list.id)"><i
                                        class="fa fa-image"></i></button>

                                    <button type="button" class="btn btn-primary"
                                            @click="imageview(list.festival_name,list['getimages'])"><i
                                        class="fa fa-file-image-o"></i></button>
                                </td>
                                <td>
                                    <button type="button" v-if="list.status != 0 " @click="changeStatus(list.id)"
                                            class="btn btn-outline-success"><i
                                        class="fa fa-check"></i></button>
                                    <button type="button" v-else @click="changeStatus(list.id)"
                                            class="btn btn-outline-danger"><i
                                        class="fa fa-ban"></i></button>
                                    <button type="button" class="btn btn-primary" @click="editForm(list)"><i
                                        class="fa fa-pencil"></i></button>
                                    <button type="button" class="btn btn-danger" @click="destroy(list.id)"><i
                                        class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr v-show="!festivals.length">
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
                            @paginate="query === '' ? getFestival() : searchData()"
                        ></pagination>

                    </div>
                </div>
            </div>
        </div>

        <!-- Create Post Model-->

        <div class="modal fade" id="modal-festival">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{editMode ?'Edit':'ADD'}} Festival</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>

                    <form @submit.prevent="editMode?update():store()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="festival">Festival Name</label>
                                        <input type="text" class="form-control"
                                               :class="{ 'is-invalid': form.errors.has('festival') }"
                                               v-model.trim="form.festival"
                                               placeholder="Enter Festival">
                                        <has-error :form="form" field="festival"></has-error>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="date">Festival Date</label>
                                        <input type="date" class="form-control"
                                               :class="{ 'is-invalid': form.errors.has('date') }"
                                               v-model.trim="form.date"
                                               placeholder="2020-01-14">
                                        <has-error :form="form" field="date"></has-error>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="info">Festival Info</label>
                                        <textarea class="form-control"
                                                  :class="{ 'is-invalid': form.errors.has('info') }"
                                                  v-model.trim="form.info"
                                                  placeholder="Something About Festival..."></textarea>
                                        <has-error :form="form" field="info"></has-error>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">{{editMode ?'Update':'ADD'}} Festival
                            </button>
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
                                <img :src="'festival/'+list.name" alt="image" class="card-img"/>
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
                editMode: false,
                imagemodaltitle: '',
                listimage: [],
                festivals: [],
                queryFiled: 'festival_name',
                query: '',
                form: new Form({
                    id: '',
                    festival: '',
                    date: '',
                    info: ''
                }), pagination: {
                    current_page: 1
                },
                dropzoneOptions: {
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
            this.getFestival()
            console.log('Component mounted.')
        }, watch: {
            query: function (newQ, old) {
                if (newQ === "") {
                    this.getFestival();
                } else {
                    this.searchData();
                }
            }
        },
        methods: {
            create: function () {
                this.editMode = false
                $("#modal-festival").modal('show')
            }, reload: function () {
                this.getFestival()
                this.$snotify.success("Festival List Refresh", "success")
            }, store: function () {
                var self = this
                this.form.busy = true;
                this.form.post('festivals').then(({data}) => {
                    if (!data.error) {
                        this.getFestival()
                        self.form.reset()
                        self.form.clear()
                        $("#modal-festival").modal('hide');
                        this.$snotify.success(data.message, "Success")
                    }
                }).catch(e => {
                    self.$snotify.error(e, "error")
                })
            }, update: function () {
                var self = this
                this.form.busy = true;
                this.form.put('festivals/' + this.form.id)
                    .then(({data}) => {
                        if (!data.error) {
                            self.editMode = false
                            self.form.reset()
                            self.form.clear()
                            self.getFestival()
                            $("#modal-festival").modal('hide');
                            self.$snotify.success(data.message, "Success")
                        }
                    }).catch(e => {
                    this.$snotify.error(e, "error")
                })
            }, getFestival: function () {
                var self = this
                axios.get("festivals?page=" + this.pagination.current_page).then(function (response) {
                    self.festivals = response.data.data;
                    self.pagination = response.data;
                }).catch(e => {
                    this.$snotify.error(e, "error")
                })
            }, searchData: function () {
                axios
                    .get(
                        "search/festivals/" +
                        this.queryFiled +
                        "/" +
                        this.query +
                        "?page=" +
                        this.pagination.current_page
                    )
                    .then(response => {
                        this.festivals = response.data.data;
                        self.pagination = response.data;
                    })
                    .catch(e => {
                        console.log(e);
                    });
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
            }, uploadSuccess: function (file, response) {
                this.getFestival()
                this.$snotify.success("Upload Image Successfully..!", "success")
            }, editForm(list) {
                this.editMode = true
                this.form.clear()
                this.form.reset()
                this.form.id = list['id']
                this.form.festival = list['festival_name']
                this.form.date = list['festival_date']
                this.form.info = list['festival_info']
                $("#modal-festival").modal('show')
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
                                    axios.delete("festivals/" + id).then(response => {
                                        this.getFestival();
                                        this.$snotify.success(
                                            "Festival Successfully Deleted",
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
                                    axios.get("status/festivals/" + id).then(response => {
                                        this.getFestival();
                                        this.$snotify.success(
                                            "Festival Status Changed Successfully.",
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
