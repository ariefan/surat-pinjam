<script type="text/babel">
    function Feedback() {
        const [data, setData] = React.useState([]);
        const [isLoading, setIsLoading] = React.useState(true);
        const [pagination, setPagination] = React.useState(null);

        React.useEffect(() => {
            // Replace 'your-api-url' with the actual URL of your API
            fetch('http://localhost:8080/api/surat-peminjaman')
                .then((response) => response.json())
                .then((responseData) => {
                    setData(responseData.data);
                    setPagination(responseData.pager);
                    setIsLoading(false);
                })
                .catch((error) => {
                    console.error('Error fetching data:', error);
                    setIsLoading(false);
                });
        }, []);

        return (
            <div>
                {isLoading ? (
                    <p>Loading...</p>
                ) : (
                    <div>
                        <table className="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nomor Surat</th>
                                    <th>Judul Surat</th>
                                    <th>Pengaju</th>
                                </tr>
                            </thead>
                            <tbody>
                                {data.map((item) => (
                                    <tr key={item.id}>
                                        <td>{item.id}</td>
                                        <td>{item.no_surat}</td>
                                        <td>{item.nama_surat}</td>
                                        <td>{item.user.nama}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                        {pagination && (
                            <div>
                                <p>Page {pagination.currentPage} of {pagination.pageCount}</p>
                                <ul className="pagination">
                                    {pagination.next && (
                                        <li className="page-item">
                                            <a className="page-link" href={pagination.next}>Next</a>
                                        </li>
                                    )}
                                    {pagination.previous && (
                                        <li className="page-item">
                                            <a className="page-link" href={pagination.previous}>Previous</a>
                                        </li>
                                    )}
                                </ul>
                            </div>
                        )}
                    </div>
                )}
            </div>
        );
    }

    const rootNode = document.getElementById("react-entry-point");
    const root = ReactDOM.createRoot(rootNode);
    root.render(React.createElement(Feedback));
</script>