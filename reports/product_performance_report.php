<?php
// reports/product_performance_report.php
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Product Performance Report</title>
  <script src="../js/vendor/html2pdf.bundle.min.js"></script>
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
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
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
      title.textContent = 'Product Performance Report';
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

      const response = await fetch('../api/custom/product-performance.php');
      const data = await response.json();
      const {
        summary,
        performance
      } = data;

      const statsContainer = document.createElement('div');
      statsContainer.className = 'stats-container';

      const cards = [{
          title: 'Best Seller',
          value: summary.best_product,
          icon: 'fa-star',
          color: '#10b981'
        },
        {
          title: 'Total Units Sold',
          value: summary.total_units,
          icon: 'fa-cubes',
          color: '#3b82f6'
        },
        {
          title: 'Total Revenue',
          value: `$${summary.total_revenue}`,
          icon: 'fa-dollar-sign',
          color: '#f59e0b'
        },
        {
          title: 'Least Seller',
          value: summary.worst_product,
          icon: 'fa-box-open',
          color: '#ef4444'
        },
      ];

      cards.forEach(card => {
        const cardEl = document.createElement('div');
        cardEl.className = 'stat-card';
        cardEl.innerHTML = `
      <div class="stat-icon" style="color: ${card.color}"><i class="fas ${card.icon}"></i></div>
      <h3 class="stat-title">${card.title}</h3>
      <div class="stat-value" style="color: ${card.color}">${card.value}</div>
    `;
        statsContainer.appendChild(cardEl);
      });
      container.appendChild(statsContainer);

      const sectionHeader = document.createElement('h2');
      sectionHeader.textContent = 'Product Performance';
      sectionHeader.style.fontSize = '1.3rem';
      sectionHeader.style.margin = '0 0 1rem';
      sectionHeader.style.color = '#2d3748';
      sectionHeader.style.display = 'flex';
      sectionHeader.style.alignItems = 'center';
      sectionHeader.style.gap = '0.5rem';
      sectionHeader.innerHTML = `<i class="fas fa-chart-line" style="color: #9333ea;"></i> Product Performance`;
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
      <th style='padding: 1rem; text-align: left;'>Rank</th>
      <th style='padding: 1rem; text-align: left;'>Product</th>
      <th style='padding: 1rem; text-align: right;'>Units Sold</th>
      <th style='padding: 1rem; text-align: right;'>Revenue ($)</th>
      <th style='padding: 1rem; text-align: right;'>Profit Margin (%)</th>
    </tr>
  `;
      table.appendChild(thead);

      const tbody = document.createElement('tbody');
      performance.sort((a, b) => b.units_sold - a.units_sold);
      performance.forEach((p, index) => {
        const row = document.createElement('tr');
        row.style.background = index % 2 === 0 ? 'white' : '#f8fafc';
        row.innerHTML = `
      <td style='padding: 1rem;'>${index + 1}</td>
      <td style='padding: 1rem;'>${p.name}</td>
      <td style='padding: 1rem; text-align: right;'>${p.units_sold}</td>
      <td style='padding: 1rem; text-align: right;'>${p.revenue.toFixed(2)}</td>
      <td style='padding: 1rem; text-align: right;'>${p.margin.toFixed(2)}</td>
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

      const opt = {
        margin: [10, 10, 10, 10],
        filename: `Product_Performance_Report_${new Date().toISOString().slice(0,10)}.pdf`,
        image: {
          type: 'jpeg',
          quality: 0.98
        },
        html2canvas: {
          scale: 2,
          useCORS: true
        },
        jsPDF: {
          unit: 'mm',
          format: 'a4',
          orientation: 'portrait'
        }
      };

      html2pdf().set(opt).from(container).save();
    })();
  </script>
</body>

</html>