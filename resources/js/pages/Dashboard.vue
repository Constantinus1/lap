<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { Head } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

const props = defineProps({
    mostOrdered: Array,
    leastOrdered: Array,
    recentOrders: Array,
});

const breadcrumbs = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const formatPrice = (price) => {
    return new Intl.NumberFormat('de-DE', {
        style: 'currency',
        currency: 'EUR',
    }).format(price);
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-4 md:p-6">
            <div class="flex flex-col gap-2">
                <h1 class="text-3xl font-bold">Dashboard</h1>
                <p class="text-muted-foreground">Statistics and recent orders overview</p>
            </div>

            <!-- Statistics Grid -->
            <div class="grid gap-6 md:grid-cols-2">
                <!-- Most Ordered Products -->
                <Card>
                    <CardHeader>
                        <CardTitle>Top 5 Most Ordered Products</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div
                                v-for="product in props.mostOrdered"
                                :key="product.id"
                                class="flex items-center gap-4"
                            >
                                <img
                                    :src="product.image"
                                    :alt="product.name"
                                    class="h-16 w-16 rounded-md object-cover"
                                />
                                <div class="flex-1">
                                    <p class="font-medium">{{ product.name }}</p>
                                    <p class="text-sm text-muted-foreground">
                                        {{ formatPrice(product.price) }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold">{{ product.total_ordered }}</p>
                                    <p class="text-xs text-muted-foreground">ordered</p>
                                </div>
                            </div>
                            <div v-if="props.mostOrdered.length === 0" class="text-center text-muted-foreground py-4">
                                No orders yet
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Least Ordered Products -->
                <Card>
                    <CardHeader>
                        <CardTitle>Top 5 Least Ordered Products</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div
                                v-for="product in props.leastOrdered"
                                :key="product.id"
                                class="flex items-center gap-4"
                            >
                                <img
                                    :src="product.image"
                                    :alt="product.name"
                                    class="h-16 w-16 rounded-md object-cover"
                                />
                                <div class="flex-1">
                                    <p class="font-medium">{{ product.name }}</p>
                                    <p class="text-sm text-muted-foreground">
                                        {{ formatPrice(product.price) }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold">{{ product.total_ordered }}</p>
                                    <p class="text-xs text-muted-foreground">ordered</p>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Recent Orders -->
            <Card>
                <CardHeader>
                    <CardTitle>Orders from Last 4 Weeks</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="py-3 text-left font-semibold">Order ID</th>
                                    <th class="py-3 text-left font-semibold">Customer</th>
                                    <th class="py-3 text-left font-semibold">Date</th>
                                    <th class="py-3 text-center font-semibold">Items</th>
                                    <th class="py-3 text-left font-semibold">Payment</th>
                                    <th class="py-3 text-right font-semibold">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="order in props.recentOrders"
                                    :key="order.id"
                                    class="border-b"
                                >
                                    <td class="py-3">#{{ order.id }}</td>
                                    <td class="py-3">
                                        <div>
                                            <p class="font-medium">{{ order.customer_name }}</p>
                                            <p class="text-xs text-muted-foreground">
                                                {{ order.customer_email }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="py-3 text-sm">{{ order.order_date }}</td>
                                    <td class="py-3 text-center">{{ order.items_count }}</td>
                                    <td class="py-3 capitalize text-sm">
                                        {{ order.payment_method.replace('_', ' ') }}
                                    </td>
                                    <td class="py-3 text-right font-medium">
                                        {{ formatPrice(order.order_amount) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-if="props.recentOrders.length === 0" class="text-center text-muted-foreground py-8">
                            No orders in the last 4 weeks
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
