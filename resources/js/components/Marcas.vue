<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Inicio card de busca-->                
                <div class="card mb-3">                    
                    <div class="card-header">Busca de marcas</div>                    
                        <div class="card-body">
                        <div class="row g-3">
                            <div class="col mb-3">
                                <input-container-component titulo="ID" id="inputId" id-help="idHelp"
                                    texto-ajuda="Opcional. Informe o id do registro">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp"
                                        placeholder="ID" v-model="busca.id">
                                </input-container-component>
                            </div>

                            <div class="col mb-3">
                                <input-container-component titulo="Nome da marca" id="inputNome" id-help="nomeHelp"
                                    texto-ajuda="Opcional. Informe o nome da marca.">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="inputNome"
                                        placeholder="Nome da marca" v-model="busca.nome">
                                </input-container-component>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex">
                        <button type="submit" class="btn btn-primary btn-sm ms-auto" @click="pesquisar()">Pesquisar</button>
                    </div>
                </div>
                <!--Fim do card de busca-->

                <!-- Inicio do card de listagem-->
                <div class="card">
                    <div class="card-header">Relação de marcas</div>
                    <div class="card-body">
                        <table-component :dados="marcas.data" :visualizar="{
                            visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaVisualizar'
                        }"
                        :atualizar="{
                            visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaAtualizar'
                        }"
                        :remover="{
                            visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaRemover'
                        }"
                        :titulos="{
                            id: { titulo: 'ID', tipo: 'texto' },
                            nome: { titulo: 'Nome', tipo: 'texto' },
                            imagem: { titulo: 'Imagem', tipo: 'imagem' },
                            created_at: { titulo: 'Data de criação', tipo: 'data' }
                        }"></table-component>
                    </div>

                    <div class="card-footer d-flex">

                        <paginate-component>
                            <li v-for="l, chaveL in marcas.links" :key="chaveL"
                                :class="l.active ? 'page-item active' : 'page-item'" @click="paginacao(l)">
                                <a class="page-link" v-html="l.label"></a>
                            </li>
                        </paginate-component>

                        <button type="button" class="btn btn-primary btn-sm ms-auto" data-bs-toggle="modal"
                            data-bs-target="#modalMarca" @click="limpaObj()">Adicionar</button>

                    </div>
                </div>
                <!-- Fim do card de listagem-->
            </div>
        </div>

        <!-- Modal de inclusão de marca -->
        <modal-component id="modalMarca" titulo="Adicionar marca">

            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Cadastro realizado com sucesso"
                    v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Erro ao tentar cadastrar a marca"
                v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>

            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Nome da marca" id="novoNome" id-help="novoNomeHelp"
                        texto-ajuda="Informe o nome da marca.">
                        <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp"
                            placeholder="Nome da marca" v-model="nomeMarca">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Imagem" id="novaImagem" id-help="novaImagemHelp"
                        texto-ajuda="Selecione uma imagem no formato PNG.">
                        <input type="file" class="form-control" id="novaImagem" aria-describedby="novaImagemHelp"
                            placeholder="Selecione uma imagem no formato PNG." @change="carregarImagem($event)">
                    </input-container-component>
                </div>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()" v-if="$store.state.transacao.status != 'sucesso'">Salvar</button>
            </template>
        </modal-component>

        <!--Modal de visualização de marca-->
        <modal-component id="modalMarcaVisualizar" titulo="Visualizar marca">
            <template v-slot:alertas></template>
            <template v-slot:conteudo>
                
                <input-container-component titulo="ID:">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>
                <input-container-component titulo="Nome da Marca:">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                </input-container-component>
                <input-container-component titulo="Imagem:">
                    <img :src="'storage/'+$store.state.item.imagem" v-if="$store.state.item.imagem" disabled>
                </input-container-component>
                <input-container-component titulo="Data de criação:">
                    <input type="text" class="form-control" :value="$filters.formatDataTempoGlobal($store.state.item.created_at)" disabled>
                </input-container-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </template>
        </modal-component>

        <!--Modal de remoção de marca-->
        <modal-component id="modalMarcaRemover" titulo="Remover marca">
            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Transação realizada com sucesso" 
                :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na transação" 
                :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">                
                <input-container-component titulo="ID:">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>
                <input-container-component titulo="Nome da Marca:">
                    <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
                </input-container-component>
                
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="remover()" v-if="$store.state.transacao.status != 'sucesso'">Remover</button>
            </template>
        </modal-component>
        

        <!-- Modal de atualização de marca -->
        <modal-component id="modalMarcaAtualizar" titulo="Atualizar marca">
            <template v-slot:alertas>
                <alert-component tipo="success" titulo="Atualização realizada com sucesso" 
                :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
                <alert-component tipo="danger" titulo="Erro na atualização" 
                :detalhes="$store.state.transacao" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>

            <template v-slot:conteudo>
                
                <div class="form-group">
                    <input-container-component titulo="Nome da marca" id="atualizarNome" id-help="atualizarNomeHelp"
                        texto-ajuda="Informe o nome da marca.">
                        <input type="text" class="form-control" id="atualizarNome" aria-describedby="atualizarNomeHelp"
                            placeholder="Nome da marca" v-model="$store.state.item.nome">
                    </input-container-component>
                </div>

                <div class="form-group">
                    <input-container-component titulo="Imagem" id="atualizarImagem" id-help="atualizarImagemHelp"
                        texto-ajuda="Selecione uma imagem no formato PNG.">
                        <input type="file" class="form-control" id="atualizarImagem" aria-describedby="atualizarImagemHelp"
                            placeholder="Selecione uma imagem no formato PNG." @change="carregarImagem($event)">
                    </input-container-component>
                </div>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="atualizar()" v-if="$store.state.transacao.status != 'sucesso'">Atualizar</button>
            </template>
            </modal-component>

    </div>
</template>

<script>
export default {
    data() {
        return {
            urlBase: 'http://localhost:8000/api/v1/marca',
            urlPaginacao: '',
            urlFiltro: '',
            nomeMarca: '',
            arquivoImagem: [],
            transacaoStatus: '',
            transacaoDetalhes: {},
            marcas: {
                data: []
            },
            busca: {
                id: '',
                nome: ''
            }
        }
    },
    methods: {
        limpaObj(){
            this.$store.state.transacao.status = '';
            this.$store.state.transacao.mensagem = '';
        },
        atualizar(){
            let formData = new FormData();
            formData.append('_method','PATCH');
            formData.append('nome',this.$store.state.item.nome);
            if(this.arquivoImagem[0]){
                formData.append('imagem',this.arquivoImagem[0]);
            }
            

            let config = {
                headers: {
                    'Content-Type': 'multipart/form-data'                    
                }
            }

            let url = this.urlBase + '/' + this.$store.state.item.id;

            axios.post(url, formData, config)
                    .then(response =>{                        
                        atualizarImagem.value = '';
                        this.$store.state.transacao.status = 'sucesso';
                        this.$store.state.transacao.mensagem = response.data.msg;
                        this.carregarLista();
                    })
                    .catch(errors => {                        
                        this.$store.state.transacao.status = 'erro';
                        this.$store.state.transacao.mensagem = errors.response.data.message;
                        this.$store.state.transacao.dados = errors.response.data.errors;
                    })

        },
        remover(){
            let url = this.urlBase + '/' + this.$store.state.item.id           
            
            let formData = new FormData();
            formData.append('_method','DELETE');      
            
            
            let config = {
                headers: {
                    'Content-Type': 'multipart/form-data'                    
                }
            }

            axios.post(url, formData, config)
                .then(response => {
                    //console.log('Registro removido com sucesso', response);
                    this.$store.state.transacao.status = 'sucesso';
                    this.$store.state.transacao.mensagem = response.data.msg;
                    this.carregarLista();
                }).catch(errors => {
                    //console.log('Houve um erro na tentativa de remoção do registro', errors.response.erro);
                    this.$store.state.transacao.status = 'erro';
                    this.$store.state.transacao.mensagem = errors.response.data.message;
                    this.$store.state.transacao.dados = errors.response.data.errors;
                })
                
            


        },
        pesquisar() {
            let filtro = '';
            for (let chave in this.busca) {
                if (this.busca[chave]) {
                    if (filtro != '') {
                        filtro += ";";
                    }

                    filtro += chave + ':like:' + this.busca[chave];
                }
            }
            if (filtro != '') {
                this.urlPaginacao = 'page=1';
                this.urlFiltro = '&filtro=' + filtro;
            } else {
                this.urlFiltro = '';
            }

            this.carregarLista();
        },
        paginacao(l) {
            if (l.url) {
                //this.urlBase = l.url;
                this.urlPaginacao = l.url.split('?')[1];
                this.carregarLista();
            }

        },
        carregarLista() {
            let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro;            

            axios.get(url)
                .then(response => {
                    this.marcas = response.data;
                })
                .catch(errors => {
                    console.log(errors);
                })
        },
        carregarImagem(e) {
            this.arquivoImagem = e.target.files
        },
        salvar() {

            let formData = new FormData();
            formData.append('nome', this.nomeMarca)
            formData.append('imagem', this.arquivoImagem[0])

            let config = {
                headers: {
                    'Content-Type': 'multipart/form-data'                  
                }
            }

            axios.post(this.urlBase, formData, config)
                .then(response => {          
                    this.$store.state.transacao.status = 'sucesso';
                    this.$store.state.transacao.mensagem = 'ID do registro: ' + response.data.id;
                    this.carregarLista();
                   
                })
                .catch(errors => {                   
                    this.$store.state.transacao.status = 'erro';
                    this.$store.state.transacao.mensagem = errors.response.data.message;
                    this.$store.state.transacao.dados = errors.response.data.errors;
                })
        }
    },
    mounted() {
        this.carregarLista()
    }
}
</script>