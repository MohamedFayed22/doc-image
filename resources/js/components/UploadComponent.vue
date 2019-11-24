<template>
    <div class="container">
        <div class="row">

            <div v-if="success != ''" class="alert alert-success col-md-12" role="alert">
                {{success}}
            </div>

            <div class="col-md-3" v-if="image">
                <img :src="image" class="img-responsive" height="70" width="90">
            </div>

            <form class="col-12 "  @submit="formSubmit" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-12">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input"  accept="image/*" v-on:change="onImageChange">
                            <label class="custom-file-label" for="customFile">Choose file</label>

                        </div>
                    </div>


                    <div class="col-12">
                        <div class="form-group">
                            <textarea class="form-control" v-model="description" placeholder="description"></textarea>
                        </div>
                    </div>


                    <div class="col-12 col-lg-6">
                        <a href="/" class="btn btn-outline-primary btn-lg btn-block mb-3">Back</a>
                    </div>
                    <div class="col-12 col-lg-6">
                        <button type="submit" class="btn btn-outline-success btn-lg btn-block mb-3" >Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                image: '',
                qr: '',
                description:'',
                success: '',
            };
        },

        methods:{
            onImageChange(e){
                console.log(e.target.files[0]);
                this.image = e.target.files[0];

                this.createImage(this.image );

            },
            createImage(file) {
                let reader = new FileReader();
                let vm = this;
                reader.onload = (e) => {
                    vm.image = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            formSubmit(e) {
                e.preventDefault();
                let currentObj = this;
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }
                let formData = new FormData();
                formData.append('image', this.image);
                formData.append('description', this.description);

                axios.post('/image/store', formData, config)
                    .then(function (response) {
                        currentObj.success = response.data.success;
                        this.allerros = [];
                    })
                    .catch(function (error) {
                        console.log(error)
                        currentObj.output = error;
                        this.success = '';

                    });

                this.description ='';
                this.image ='';
            }
        }

    }


</script>
