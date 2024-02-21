<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <td v-for="t, key in titulos" :key="key">
                    <th scope="col" class="text-uppercase">{{ t }}</th>
                    </td>
                </tr>

            </thead>
            <tbody>
                <tr v-for="obj in dados" :key="obj.id">
                    <template v-for="valor, chave in obj">
                        <td v-if="titulos.includes(chave)" :key="chave">
                            <span v-if="chave == 'imagem'">
                                <img :src="'/storage/'+valor" width="40" height="40">
                            </span>
                            <span v-else>{{ valor }}</span>
                        </td>
                    </template>
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
    ]

}
</script>