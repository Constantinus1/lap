<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Head, router } from '@inertiajs/vue3';
import { ShoppingCart } from 'lucide-vue-next';

const props = defineProps({
    products: {
        type: Array,
        required: true,
    },
});

const breadcrumbs = [
    {
        title: 'Shop',
        href: '/shop',
    },
];

const formatPrice = (price) => {
    return new Intl.NumberFormat('de-DE', {
        style: 'currency',
        currency: 'EUR',
    }).format(price);
};

const addToCart = (productId) => {
    router.post('/cart/add', {
        product_id: productId,
        quantity: 1,
    }, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Shop" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-4 md:p-6">
            <div class="flex flex-col gap-2">
                <h1 class="text-3xl font-bold">Shop</h1>
                <p class="text-muted-foreground">Browse our collection of products</p>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <Card v-for="product in products" :key="product.id" class="flex flex-col">
                    <a :href="`/products/${product.id}`" class="block">
                        <div class="aspect-square overflow-hidden rounded-t-lg bg-muted">
                            <img
                                :src="product.image"
                                :alt="product.name"
                                class="h-full w-full object-cover transition-transform hover:scale-105"
                            />
                        </div>
                    </a>
                    <CardHeader>
                        <a :href="`/products/${product.id}`">
                            <CardTitle class="line-clamp-1 hover:text-primary transition-colors">
                                {{ product.name }}
                            </CardTitle>
                        </a>
                        <CardDescription class="line-clamp-2">
                            {{ product.description }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="flex-1">
                        <p class="text-2xl font-bold">{{ formatPrice(product.price) }}</p>
                    </CardContent>
                    <CardFooter class="flex gap-2">
                        <Button class="w-full" @click="addToCart(product.id)">
                            <ShoppingCart class="mr-2 h-4 w-4" />
                            Add to Cart
                        </Button>
                    </CardFooter>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
