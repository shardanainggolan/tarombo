<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Pohon Keluarga</h1>
    <div class="form-group">
        <label for="individual_id">Pilih Individu untuk Memulai Pohon Keluarga</label>
        <select id="individual_id" class="form-control">
            @foreach ($individuals as $individual)
                <option value="{{ $individual->id }}">{{ $individual->first_name }} {{ $individual->last_name }}</option>
            @endforeach
        </select>
        <button id="load-tree" class="btn btn-primary mt-2">Tampilkan Pohon</button>
    </div>
    <div id="tree-container" style="width: 100%; height: 600px;"></div>

    <!-- Tambahkan dTree dari CDN -->
    <script src="https://cdn.jsdelivr.net/npm/d3-dtree@2.4.1/dist/dTree.min.js"></script>
    <script>
        document.getElementById('load-tree').addEventListener('click', function() {
            const individualId = document.getElementById('individual_id').value;
            loadFamilyTree(individualId);
        });

        function loadFamilyTree(individualId) {
            fetch(`/api/family-tree/${individualId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    // Tambahkan header autentikasi jika diperlukan: 'Authorization': 'Bearer ' + token
                }
            })
            .then(response => response.json())
            .then(data => {
                drawTree(data);
            })
            .catch(error => console.error('Error:', error));
        }

        function drawTree(data) {
            // Kosongkan container sebelum menggambar ulang
            document.getElementById('tree-container').innerHTML = '';

            // Format data untuk dTree
            const familyData = transformDataForDTree(data);

            // Inisialisasi dTree
            dTree.init(familyData, {
                target: '#tree-container',
                width: '100%',
                height: 600,
                callbacks: {
                    nodeRenderer: function(name, x, y, height, width, extra, id, nodeClass, textClass) {
                        // Sesuaikan tampilan node
                        return `
                            <g class="${nodeClass}" transform="translate(${x},${y})">
                                <rect width="${width}" height="${height}" rx="5" fill="${extra.gender === 'male' ? '#3498db' : '#e91e63'}"></rect>
                                <text class="${textClass}" x="${width / 2}" y="${height / 2}" dy=".35em" text-anchor="middle">${name}</text>
                            </g>
                        `;
                    }
                }
            });
        }

        function transformDataForDTree(data) {
            const nodes = [];
            const seenIds = new Set();

            function addNode(individual) {
                if (seenIds.has(individual.id)) return individual.id;
                seenIds.add(individual.id);

                const node = {
                    id: individual.id,
                    name: individual.name,
                    extra: { gender: individual.gender },
                    marriages: []
                };

                // Tambahkan pernikahan dan pasangan
                if (individual.spouses && individual.spouses.length > 0) {
                    individual.spouses.forEach(spouse => {
                        const spouseId = addNode(spouse);
                        node.marriages.push({
                            spouse: { id: spouseId },
                            children: individual.children
                                .filter(child => {
                                    // Asumsikan anak dari pernikahan ini (logika bisa disesuaikan)
                                    return true;
                                })
                                .map(child => ({ id: addNode(child) }))
                        });
                    });
                } else if (individual.children && individual.children.length > 0) {
                    // Jika tidak ada pasangan tapi ada anak
                    node.marriages.push({
                        spouse: null,
                        children: individual.children.map(child => ({ id: addNode(child) }))
                    });
                }

                nodes.push(node);
                return individual.id;
            }

            addNode(data);
            return nodes;
        }
    </script>

    <style>
        #tree-container svg {
            border: 1px solid #ddd;
        }
    </style>
</body>
</html>