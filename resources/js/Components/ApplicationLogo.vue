<template>
    <img
        src="../../img/phone-pad-service-main-logo.jpg"
        alt="logo"
        :style="logoStyle"
    >
</template>

<script>
export default {
    data() {
        return {
            angle: 0,
            animationSpeed: 0.05
        }
    },
    computed: {
        logoStyle() {
            return {
                transform: `rotate(${Math.sin(this.angle) * 5}deg)`,
                transition: 'transform 0.3s ease',
                display: 'inline-block' // Необходимо для transform
            }
        }
    },
    mounted() {
        this.animateLogo()
    },
    methods: {
        animateLogo() {
            const animate = () => {
                this.angle += this.animationSpeed
                requestAnimationFrame(animate)
            }
            animate()
        }
    }
}
</script>
