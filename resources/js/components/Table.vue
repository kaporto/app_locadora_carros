<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <td v-for="t, key in titulos" :key="key">
                    <th scope="col">{{ t }}</th>
                    </td>
                </tr>

            </thead>
            <tbody>
                <tr v-for="m in dados" :key="m.id">
                    <th scope="row">{{ m.id }}</th>
                    <td>{{ m.nome }}</td>
                    <td><img :src="'/storage/' + m.imagem" width="40" height="40"></td>
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