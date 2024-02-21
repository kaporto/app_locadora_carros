<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <td v-for="t, key in titulos" :key="key">
                    <th scope="col">{{ t.titulo }}</th>
                    </td>
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
                </tr>               
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: [
        'dados',
        'titulos'
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
        formatDate(value) {
          const parts = value.substring(0, 10).split('-');
          return `${parts[2]}/${parts[1]}/${parts[0]}`;
        }
    }

}
</script>