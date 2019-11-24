<template >
    <div class="container">

        <div class="alert alert-danger col-md-12" role="alert" v-if="error">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            @{{ error }}
        </div>

        <div class="row">

            <div class="col-12 col-lg-3">
                <div class="sidebare">
                    <div class="block">
                        <h3>Search Results:</h3>
                        <ul v-for="result in results">
                            <li @click="findImage(result.id)">  {{result.description}}</li>
                        </ul>
                    </div>
                    <div class="block">
                        <h3>Recent Docs:</h3>
                        <ul v-for="last in latest">
                            <li @click="findImage(last.id)">  {{last.image_name}}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="details col-12 col-lg-6">


                    <div class="row">
                        <div class="col-12 col-lg-8">
                            <input type="text" class="form-control mb-2 mr-sm-2"   v-on:keyup.enter="search()" v-model.lazy="keywords"   placeholder="Search ...">
                        </div>
                        <div class="col-12 col-lg-4">
                            <button class="btn btn-primary mb-2  btn-block" type="button" @click="search()"   v-if="!loading">Search!</button>
                            <button class="btn btn-primary mb-2  btn-block" type="button" disabled="disabled"  v-if="loading">Searching...</button>
                        </div>
                    </div>



                <div class="row">
                    <div class="col-12 col-lg-8">
                <select @change="changecss" class="form-control" v-model="pagesize">
                    <option disabled value="">Please select one</option>
                    <option>A3</option>
                    <option>A3 landscape</option>
                    <option selected>A4</option>
                    <option>A4 landscape</option>
                    <option>A5</option>
                    <option>A5 landscape</option>
                </select>
                        <span>Selected: {{ pagesize }}</span><br />
                </div>
                </div>


                <div class="row">
                    <div class="sheet padding-10mm printonly" id="section-to-print" v-bind:style="{ padding: '15px', height: bodyblockheight + 'mm' }">
                        <div class="img-show"  >
                            <img src="templates/images/logo.png"  v-if="ShowImage"/>
                            <img :src="'/images/'+image"  v-if="!ShowImage"/>
                        </div>
                    </div>
                    <div class="col-12 col-lg-10">
                        <h2>Image Name</h2>
                    </div>
                    <div class="col-12 col-lg-2">
                        <button type="button" class="btn btn-primary mb-2  btn-block"  @click="printpage">print</button>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="sidebare">
                    <div class="block">
                        <img src="templates/images/QR_Code.png" v-if="QrShow"/>
                        <qrcode-vue :value="valueQr" :size="size" v-if="!QrShow" level="H"></qrcode-vue>
                        <button type="button" class="btn btn-primary mb-2  btn-block" @click="printpage">print</button>
                    </div>
                    <div class="block">
                        <img src="templates/images/barcode.png" v-if="code" />
                        <barcode :value="valueBarCode" :options="{ lineColor: '#0275d8', text: 'Scan'}" v-if="!code"></barcode>
                        <button type="button" class="btn btn-primary mb-2  btn-block" @click="printpage">print</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import QrcodeVue from 'qrcode.vue'

    export default {
        components: {
            QrcodeVue

        },
        data() {
            return {
                pagesize:'A4',
                keywords: '',
                results: [],
                loading: false,
                error: false,
                latest:[],
                valueQr:'',
                valueBarCode:'',
                QrShow:true,
                size: 200,
                code:true,
                ShowImage:true,
                image:''
            };
        },
        created: function () {
            axios.post('/latest').then((response) => {
                this.latest = response.data;
            });
        },

        computed:{
            bodyblockheight(){
                let size = 0;
                switch (this.pagesize)
                {
                    case "A3":
                        size = 419;
                        break;
                    case "A3 landscape":
                        size = 296;
                        break;
                    case "A4 landscape":
                        size = 209;
                        break;
                    case "A5 landscape":
                        size = 147;
                        break;
                    case "A5":
                        size = 209;
                        break;
                    default:
                        size = 296;
                }
                console.log('new size =',size-40)
                return (size - 40 );
            },
        },

        methods: {


            cssPagedMedia: (function () {
                var style = document.createElement('style');
                document.head.appendChild(style);
                return function (rule) {
                    style.innerHTML = rule;
                };
            }()),
            changecss(){
                this.cssPagedMedia.size = (size)=>{
                    this.cssPagedMedia('@page {size: ' + size + '}');
                };
                this.cssPagedMedia.size(this.pagesize);
            },
            printpage(){
                //const prtHtml = document.getElementById('imagess').innerHTML;
                //console.log(prtHtml);
               // document.body.innerHTML = prtHtml;
                window.print();
               // prtHtml.print();
               // document.body.innerHTML =''
                // WinPrint.print()
            },
            findImage:function(id){
                this.QrShow =false;
                this.code=false;
                this.ShowImage =false;

                    axios.post('/data-image',{id:id}).then((response) => {
                   // console.log(response.data.image_name)
                        this.valueQr = response.data.Qr;
                        this.valueBarCode = response.data.barCode;
                        this.image = response.data.image_name;

                });
            },
            search: function() {
                this.error = '';
                this.results = [];
                this.loading = true;
                axios.post('/search?q=' + this.keywords).then((response) => {
                    response.data.error ? this.error = response.data.error : this.results = response.data;
                    this.loading = false;
                    this.keywords = '';
                });
            }

        }

    }
</script>


<style>

    @page { margin: 0 }

    .sheet {
        margin: 0;
        overflow: hidden;
        position: relative;
        box-sizing: border-box;
        page-break-after: always;
    }

    .A3 .sheet { width: 297mm; height: 419mm }
    .A3.landscape .sheet { width: 420mm; height: 296mm }
    .A4 .sheet { width: 210mm; height: 296mm }
    .A4.landscape .sheet { width: 297mm; height: 209mm }
    .A5 .sheet { width: 148mm; height: 209mm }
    .A5.landscape .sheet { width: 210mm; height: 147mm }

    /** Padding area **/
    .sheet.padding-10mm { padding: 10mm }
    .sheet.padding-15mm { padding: 15mm }
    .sheet.padding-20mm { padding: 20mm }
    .sheet.padding-25mm { padding: 25mm }


    /** Fix for Chrome issue #273306 **/
    @media print {
        body * {
            visibility: hidden;
        }
        #section-to-print, #section-to-print * {
            visibility: visible;
        }
        #section-to-print {
            position: absolute;
            left: 0;
            top: 0;
        }
        /*
                  body.A3.landscape { width: 420mm }
                  body.A3, body.A4.landscape { width: 297mm }
                  body.A4, body.A5.landscape { width: 210mm }
                  body.A5 { width: 148mm }*/
        .printonly { display:block; }
        .noprint { display: none }
        .A3.landscape { width: 420mm }
        .A3,.A4.landscape { width: 297mm }
        .A4,.A5.landscape { width: 210mm }
        .A5 { width: 148mm }
    }

    @page {
        size: A4;
        width: 210mm;
        height: 296mm;
        margin: 0;
    }
</style>





