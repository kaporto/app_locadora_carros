<template>
    <div>       
        <table class="table table-hover">
            <thead>
                <tr>
                    <td v-for="t, key in titulos" :key="key">
                        <th scope="col">{{ t.titulo }}</th>
                    </td>
                    <th v-if="visualizar.visivel || atualizar || remover"></th>                    
                </tr>

            </thead>
            <tbody>
                <tr v-for="obj,chave in dadosFiltrados" :key="chave">                    
                    <td v-for="valor, chaveValor in obj" :key="chaveValor">
                        <span v-if="titulos[chaveValor].tipo == 'texto'">{{ valor }}</span>
                        <span v-if="titulos[chaveValor].tipo == 'data'">{{ formatDate(valor) }}</span>
                        <span v-if="titulos[chaveValor].tipo == 'imagem'">
                            <img :src="'/storage/'+valor" width="40" height="40">
                        </span>                          
                     </td>
                     <td>
                        <button v-if="visualizar.visivel" class="btn btn-outline-info btn-sm" :data-bs-toggle="visualizar.dataToggle" 
                        :data-bs-target="visualizar.dataTarget" @click="setStore(obj)">Visualizar</button>   
                        <button v-if="atualizar" class="btn btn-outline-primary btn-sm">Atualizar</button> 
                        <button v-if="remover.visivel" class="btn btn-outline-danger btn-sm" :data-bs-toggle="remover.dataToggle" 
                        :data-bs-target="remover.dataTarget" @click="setStore(obj)">Remover</button>
                     </td>
                </tr>               
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: [
        'dados',
        'titulos',
        'atualizar',
        'visualizar',
        'remover'
    ],    
    computed:{
        dadosFiltrados(){
            let campos = Object.keys(this.titulos);
            let dadosFiltrados = []
            this.dados.map((item,chave) => {

                let itemFiltrado = {}
                campos.forEach(campo => {                    
                    itemFiltrado[campo] = item[campo];                    
                    
                })               
                dadosFiltrados.push(itemFiltrado);
            })
            return dadosFiltrados;
        }
    },
    methods: {
        setStore(obj){
            this.$store.state.transacao.status = '';
            this.$store.state.transacao.mensagem = '';
            this.$store.state.item = obj;            
        },
        formatDate(value) {
          const parts = value.substring(0, 10).split('-');
          return `${parts[2]}/${parts[1]}/${parts[0]}`;
        }
    }

}
</script>