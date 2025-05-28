<?php
// inventory_report.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Status Report</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .stats-container {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        .stat-card {
            flex: 0 1 200px;
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            text-align: center;
            min-width: 0;
        }
        .stat-icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        .stat-title {
            margin: 0 0 0.5rem;
            font-size: 0.9rem;
            font-weight: 500;
            color: #718096;
        }
        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
        }
    </style>
</head>
<body>
<script>
(async () => {
    // Create container with modern styling
    const container = document.createElement('div');
    container.style.padding = '2.5rem';
    container.style.fontFamily = "'Inter', sans-serif";
    container.style.maxWidth = '1000px';
    container.style.margin = '0 auto';
    container.style.color = '#2d3748';

    // Header with gradient background
    const header = document.createElement('div');
    header.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
    header.style.color = 'white';
    header.style.padding = '1.5rem 2rem';
    header.style.borderRadius = '8px';
    header.style.marginBottom = '2rem';
    header.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';
    
    const title = document.createElement('h1');
    title.textContent = 'Inventory Status Report';
    title.style.margin = '0';
    title.style.fontSize = '1.8rem';
    title.style.fontWeight = '600';
    header.appendChild(title);

    const date = document.createElement('p');
    date.style.margin = '0.5rem 0 0';
    date.style.opacity = '0.9';
    date.style.fontSize = '0.9rem';
    date.style.fontWeight = '300';
    date.innerHTML = `<i class="far fa-calendar-alt"></i> Generated on: ${new Date().toLocaleString()}`;
    header.appendChild(date);

    container.appendChild(header);

    // Stats cards - now perfectly centered and aligned
    const statsRes = await fetch('../api/product-stats.php');
    const stats = await statsRes.json();

    const statsContainer = document.createElement('div');
    statsContainer.className = 'stats-container';

    const statCards = [
        {
            title: 'Total Products',
            value: stats.totalProducts,
            icon: 'fas fa-boxes',
            color: '#4f46e5'
        },
        {
            title: 'Low Stock Items',
            value: stats.lowStockCount,
            icon: 'fas fa-exclamation-triangle',
            color: '#f59e0b'
        },
        {
            title: 'Categories',
            value: stats.categoryCount,
            icon: 'fas fa-tags',
            color: '#10b981'
        },
        {
            title: 'Suppliers',
            value: stats.supplierCount,
            icon: 'fas fa-truck',
            color: '#3b82f6'
        }
    ];

    statCards.forEach(card => {
        const cardEl = document.createElement('div');
        cardEl.className = 'stat-card';
        
        cardEl.innerHTML = `
            <div class="stat-icon" style="color: ${card.color}">
                <i class="${card.icon}"></i>
            </div>
            <h3 class="stat-title">${card.title}</h3>
            <div class="stat-value" style="color: ${card.color}">${card.value}</div>
        `;
        
        statsContainer.appendChild(cardEl);
    });

    container.appendChild(statsContainer);

    // Section header
    const sectionHeader = document.createElement('h2');
    sectionHeader.textContent = 'Low Stock Products';
    sectionHeader.style.fontSize = '1.3rem';
    sectionHeader.style.margin = '0 0 1rem';
    sectionHeader.style.color = '#2d3748';
    sectionHeader.style.display = 'flex';
    sectionHeader.style.alignItems = 'center';
    sectionHeader.style.gap = '0.5rem';
    sectionHeader.innerHTML = `<i class="fas fa-exclamation-circle" style="color: #f59e0b;"></i> Low Stock Products`;
    container.appendChild(sectionHeader);

    // Fetch low stock data
    const tableRes = await fetch('../api/low-stock-products.php');
    const products = await tableRes.json();

    const table = document.createElement('table');
    table.style.width = '100%';
    table.style.borderCollapse = 'separate';
    table.style.borderSpacing = '0';
    table.style.borderRadius = '8px';
    table.style.overflow = 'hidden';
    table.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)';
    
    // Table header
    const thead = document.createElement('thead');
    thead.style.background = '#f7fafc';
    thead.style.color = '#4a5568';
    thead.innerHTML = `
        <tr>
            <th style='padding: 1rem; text-align: left; font-weight: 600; border-bottom: 1px solid #e2e8f0;'>Product</th>
            <th style='padding: 1rem; text-align: left; font-weight: 600; border-bottom: 1px solid #e2e8f0;'>Category</th>
            <th style='padding: 1rem; text-align: right; font-weight: 600; border-bottom: 1px solid #e2e8f0;'>Current Stock</th>
            <th style='padding: 1rem; text-align: right; font-weight: 600; border-bottom: 1px solid #e2e8f0;'>Min Required</th>
            <th style='padding: 1rem; text-align: center; font-weight: 600; border-bottom: 1px solid #e2e8f0;'>Status</th>
        </tr>
    `;
    table.appendChild(thead);
    
    // Table body
    const tbody = document.createElement('tbody');
    products.forEach((p, index) => {
        const row = document.createElement('tr');
        row.style.background = index % 2 === 0 ? 'white' : '#f8fafc';
        
        const statusColor = p.quantity <= 0 ? '#ef4444' : '#f59e0b';
        const statusText = p.quantity <= 0 ? 'Critical' : 'Warning';
        
        row.innerHTML = `
            <td style='padding: 1rem; border-bottom: 1px solid #e2e8f0; font-weight: 500;'>${p.name}</td>
            <td style='padding: 1rem; border-bottom: 1px solid #e2e8f0; color: #4a5568;'>${p.category}</td>
            <td style='padding: 1rem; border-bottom: 1px solid #e2e8f0; text-align: right; font-weight: 500;'>${p.quantity} ${p.unit || ''}</td>
            <td style='padding: 1rem; border-bottom: 1px solid #e2e8f0; text-align: right; color: #4a5568;'>${p.min_stock} ${p.unit || ''}</td>
            <td style='padding: 1rem; border-bottom: 1px solid #e2e8f0; text-align: center;'>
                <span style="background: ${statusColor}20; color: ${statusColor}; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.8rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.25rem;">
                    ${p.quantity <= 0 ? '<i class="fas fa-times-circle"></i>' : '<i class="fas fa-exclamation-triangle"></i>'} 
                    ${statusText}
                </span>
            </td>
        `;
        tbody.appendChild(row);
    });
    table.appendChild(tbody);
    container.appendChild(table);

    // Footer
    const footer = document.createElement('div');
    footer.style.marginTop = '2rem';
    footer.style.paddingTop = '1rem';
    footer.style.borderTop = '1px solid #e2e8f0';
    footer.style.color = '#718096';
    footer.style.fontSize = '0.8rem';
    footer.style.textAlign = 'center';
    container.appendChild(footer);

    document.body.appendChild(container);
    document.body.style.visibility = 'visible';

    // Auto-generate and download PDF
    const opt = {
    margin: [10, 10, 10, 10],
    filename: `Inventory_Status_Report_${new Date().toISOString().slice(0,10)}.pdf`,
    image: { type: 'jpeg', quality: 0.98 },
    html2canvas: { scale: 2, useCORS: true },
    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
};

    
    html2pdf().set(opt).from(container).save();
})();
</script>
</body>
</html>