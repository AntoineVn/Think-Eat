<template>
    <header>
        <h3>{{ title }}</h3>

    </header>
</template>

<script>

export default {
    data() {
        return{
            title: "Header Title"
        }
    }
    
}
</script>

<style scoped>
header {
    background-color: darkgrey;
    padding: 10px;
    height: 50px;
    text-align: center;
}
</style>