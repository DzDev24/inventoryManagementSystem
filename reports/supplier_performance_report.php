<?php
// reports/supplier_performance_report.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Supplier Performance Report</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      visibility: hidden;
    }
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
  const container = document.createElement('div');
  container.style.padding = '2.5rem';
  container.style.fontFamily = "'Inter', sans-serif";
  container.style.maxWidth = '1000px';
  container.style.margin = '0 auto';
  container.style.color = '#2d3748';

  const header = document.createElement('div');
  header.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
  header.style.color = 'white';
  header.style.padding = '1.5rem 2rem';
  header.style.borderRadius = '8px';
  header.style.marginBottom = '2rem';
  header.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';

  const title = document.createElement('h1');
  title.textContent = 'Supplier Performance Report';
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

  const res = await fetch('../api/custom/supplier-performance.php');
  const { summary, suppliers } = await res.json();

  const statsContainer = document.createElement('div');
  statsContainer.className = 'stats-container';

  const statCards = [
    { title: 'Top Supplier', value: summary.top_supplier, icon: 'fas fa-trophy', color: '#4f46e5' },
    { title: 'Total Products Supplied', value: summary.total_products, icon: 'fas fa-boxes', color: '#3b82f6' },
    { title: 'Active Suppliers', value: summary.active_suppliers, icon: 'fas fa-user-check', color: '#10b981' },
    { title: 'Avg Products per Supplier', value: summary.avg_products_per_supplier.toFixed(2), icon: 'fas fa-percentage', color: '#f59e0b' }
  ];

  statCards.forEach(card => {
    const cardEl = document.createElement('div');
    cardEl.className = 'stat-card';
    cardEl.innerHTML = `
      <div class="stat-icon" style="color: ${card.color}"><i class="${card.icon}"></i></div>
      <h3 class="stat-title">${card.title}</h3>
      <div class="stat-value" style="color: ${card.color}">${card.value}</div>
    `;
    statsContainer.appendChild(cardEl);
  });

  container.appendChild(statsContainer);

  const sectionHeader = document.createElement('h2');
  sectionHeader.textContent = 'Supplier Details';
  sectionHeader.style.fontSize = '1.3rem';
  sectionHeader.style.margin = '2rem 0 1rem';
  sectionHeader.style.color = '#2d3748';
  sectionHeader.style.display = 'flex';
  sectionHeader.style.alignItems = 'center';
  sectionHeader.style.gap = '0.5rem';
  sectionHeader.innerHTML = `<i class="fas fa-list-alt" style="color: #6366f1;"></i> Supplier Details`;
  container.appendChild(sectionHeader);

  const table = document.createElement('table');
  table.style.width = '100%';
  table.style.borderCollapse = 'separate';
  table.style.borderSpacing = '0';
  table.style.borderRadius = '8px';
  table.style.overflow = 'hidden';
  table.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)';

  const thead = document.createElement('thead');
  thead.style.background = '#f7fafc';
  thead.style.color = '#4a5568';
  thead.innerHTML = `
    <tr>
      <th style='padding: 1rem; text-align: left;'>Supplier Name</th>
      <th style='padding: 1rem; text-align: left;'>Company</th>
      <th style='padding: 1rem; text-align: right;'>Products Supplied</th>
      <th style='padding: 1rem; text-align: right;'>Phone</th>
    </tr>
  `;
  table.appendChild(thead);

  const tbody = document.createElement('tbody');
  suppliers.sort((a, b) => b.product_count - a.product_count);
  suppliers.forEach((s, index) => {
    const row = document.createElement('tr');
    row.style.background = index % 2 === 0 ? 'white' : '#f8fafc';
    row.innerHTML = `
      <td style='padding: 1rem;'>${s.name}</td>
      <td style='padding: 1rem;'>${s.company}</td>
      <td style='padding: 1rem; text-align: right;'>${s.product_count}</td>
      <td style='padding: 1rem; text-align: right;'>${s.phone}</td>
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

  html2pdf().set({
    margin: [10, 10, 10, 10],
    filename: `Supplier_Performance_Report_${new Date().toISOString().slice(0, 10)}.pdf`,
    image: { type: 'jpeg', quality: 0.98 },
    html2canvas: { scale: 2, useCORS: true },
    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
  }).from(container).save();
})();
</script>
</body>
</html>
