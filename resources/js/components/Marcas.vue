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
                                        placeholder="ID">
                                </input-container-component>
                            </div>

                            <div class="col mb-3">
                                <input-container-component titulo="Nome da marca" id="inputNome" id-help="nomeHelp"
                                    texto-ajuda="Opcional. Informe o nome da marca.">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="inputNome"
                                        placeholder="Nome da marca">
                                </input-container-component>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex">
                        <button type="submit" class="btn btn-primary btn-sm ms-auto">Pesquisar</button>
                    </div>
                </div>
                <!--Fim do card de busca-->

                <!-- Inicio do card de listagem-->
                <div class="card">
                    <div class="card-header">Relação de marcas</div>
                    <div class="card-body">
                        <table-component></table-component>
                    </div>

                    <div class="card-footer d-flex">
                        <button type="button" class="btn btn-primary btn-sm ms-auto" data-bs-toggle="modal"
                            data-bs-target="#modalMarca">Adicionar</button>
                    </div>
                </div>
                <!-- Fim do card de listagem-->
            </div>
        </div>

        <!-- Modal -->
        <modal-component id="modalMarca" titulo="Adicionar marca">

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
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
            </template>


        </modal-component>
    </div>
</template>

<script>
import axios from 'axios';


export default {
    data() {
        return {
            urlBase: 'http://localhost:8000/api/v1/marca',
            nomeMarca: '',
            arquivoImagem: []
        }
    },
    methods: {
        carregarImagem(e) {
            this.arquivoImagem = e.target.files;
        },
        salvar() {
            let formData = new FormData();
            formData.append('nome', this.nomeMarca);
            formData.append('imagem', this.arquivoImagem[0]);

            let config = {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Accept': 'application/json'
                }
            }
            axios.post(this.urlBase, formData, config)
                .then(response => {
                    console.log(response);
                })
                .catch(errors => {
                    console.log(errors);
                })
        }
    }

}
</script>