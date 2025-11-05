<div class="bg-gradient-to-br from-slate-800 to-slate-900 border border-blue-500/20 rounded-xl p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-white">Active Deliveries</h2>
        <button class="px-4 py-2 bg-blue-500/20 text-blue-300 rounded-lg hover:bg-blue-500/30 transition-colors text-sm">
            View All
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-blue-500/20">
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Order ID</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Recipient</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Location</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Status</th>
                    <th class="text-left py-3 px-4 text-gray-400 font-medium">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-blue-500/10 hover:bg-blue-500/10 transition-colors">
                    <td class="py-4 px-4 text-white font-medium">DLV-0001</td>
                    <td class="py-4 px-4 text-gray-300">John Smith</td>
                    <td class="py-4 px-4 text-gray-300">Downtown Area</td>
                    <td class="py-4 px-4">
                        <span class="px-3 py-1 bg-blue-500/30 text-blue-200 rounded-full text-xs font-medium">In Transit</span>
                    </td>
                    <td class="py-4 px-4 text-white font-medium">$12.50</td>
                </tr>
                <tr class="border-b border-blue-500/10 hover:bg-blue-500/10 transition-colors">
                    <td class="py-4 px-4 text-white font-medium">DLV-0002</td>
                    <td class="py-4 px-4 text-gray-300">Jane Doe</td>
                    <td class="py-4 px-4 text-gray-300">Midtown</td>
                    <td class="py-4 px-4">
                        <span class="px-3 py-1 bg-green-500/30 text-green-200 rounded-full text-xs font-medium">Delivered</span>
                    </td>
                    <td class="py-4 px-4 text-white font-medium">$15.00</td>
                </tr>
                <tr class="hover:bg-blue-500/10 transition-colors">
                    <td class="py-4 px-4 text-white font-medium">DLV-0003</td>
                    <td class="py-4 px-4 text-gray-300">Mike Johnson</td>
                    <td class="py-4 px-4 text-gray-300">Uptown</td>
                    <td class="py-4 px-4">
                        <span class="px-3 py-1 bg-orange-500/30 text-orange-200 rounded-full text-xs font-medium">Pending</span>
                    </td>
                    <td class="py-4 px-4 text-white font-medium">$10.25</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
