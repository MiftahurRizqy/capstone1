<script>
    document.addEventListener("DOMContentLoaded", function () {
        /**
         * Check all the permissions
         */
        const checkPermissionAll = document.getElementById("checkPermissionAll");
        checkPermissionAll.addEventListener("click", function () {
            const isChecked = this.checked;
            // Check all permission checkboxes
            document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            // Check all group checkboxes
            document.querySelectorAll('.mb-6 input[type="checkbox"]:not([name="permissions[]"])').forEach(checkbox => {
                checkbox.checked = isChecked;
            });
        });

        function implementAllChecked() {
            const permissionCheckboxes = document.querySelectorAll('input[name="permissions[]"]');
            const allChecked = Array.from(permissionCheckboxes).every(checkbox => checkbox.checked);
            checkPermissionAll.checked = allChecked;
        }

        document.querySelectorAll('input[name="permissions[]"]').forEach(permissionCheckbox => {
            permissionCheckbox.addEventListener('change', () => {
                implementAllChecked();
                // Update group checkbox status
                const groupContainer = permissionCheckbox.closest('.mb-6');
                const groupCheckbox = groupContainer.querySelector('input[type="checkbox"]:not([name="permissions[]"])');
                const permissionCheckboxesInGroup = groupContainer.querySelectorAll('input[name="permissions[]"]');
                const allInGroupChecked = Array.from(permissionCheckboxesInGroup).every(cb => cb.checked);
                groupCheckbox.checked = allInGroupChecked;
            });
        });

        document.querySelectorAll('.mb-6 input[type="checkbox"]:not([name="permissions[]"])').forEach(groupCheckbox => {
            groupCheckbox.addEventListener('change', function() {
                const groupContainer = this.closest('.mb-6');
                const permissionCheckboxes = groupContainer.querySelectorAll('input[name="permissions[]"]');
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                implementAllChecked();
            });
        });

        // Initial check
        implementAllChecked();
    });
</script>