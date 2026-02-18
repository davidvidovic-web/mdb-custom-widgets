/**
 * MDB Comparison Table Widget JS
 */
jQuery(window).on('elementor/frontend/init', function() {
    elementorFrontend.hooks.addAction('frontend/element_ready/mdb-comparison-table.default', function($scope, $) {
        var $tabs = $scope.find('.mdb-comparison-tab');
        var $rows = $scope.find('.mdb-comparison-row');
        
        // Function to update striping on visible rows
        function updateStriping() {
            var visibleIndex = 0;
            $rows.each(function() {
                var $row = $(this);
                if ($row.css('display') !== 'none') {
                    // Reset inline styles for bg (if we used classes it would be cleaner, but let's try to simulate nth-child)
                    // Actually, the CSS :nth-child(odd) works on the DOM order.
                    // If we hide elements with display:none, they are still in DOM.
                    // So we must manually assign odd/even classes or styles.
                    
                    // We will remove style overrides first
                    $row.removeClass('mdb-row-odd mdb-row-even');
                    
                    if (visibleIndex % 2 === 0) {
                        $row.addClass('mdb-row-odd'); // 1st (0) is Odd visually (Row 1)
                        // Wait, index 0 is 1st row. 0 is even number but 1st item.
                        // Lets follow CSS: nth-child(1) is odd.
                        // visibleIndex 0 -> Row 1 -> Odd.
                    } else {
                        $row.addClass('mdb-row-even');
                    }
                    visibleIndex++;
                }
            });
        }

        // Initialize: Show rows for first active tab
        var initialTab = $scope.find('.mdb-comparison-tab.active').data('tab');
        if (initialTab) {
            filterRows(initialTab);
        }

        $tabs.on('click', function() {
            var $this = $(this);
            var category = $this.data('tab');
            
            // Toggle Active Class
            $tabs.removeClass('active');
            $this.addClass('active');
            
            filterRows(category);
        });

        function filterRows(category) {
            $rows.each(function() {
                var $row = $(this);
                if ($row.data('category') == category) {
                    $row.show();
                } else {
                    $row.hide();
                }
            });
            
            // Re-calc striping if we want perfect zebra (CSS nth-child breaks with hidden items)
            // But for now, let's see if the CSS handles it or if we need the JS fix.
            // The CSS uses :nth-child which counts hidden items.
            // So we definitely need to manually stripe.
            
            // Let's rely on CSS first to see if it's acceptable (stripes might look random).
            // Actually, with display:none, the stripes WILL look random (e.g. two whites in a row).
            // So we really should fix it.
            
            // Add helper classes in JS, removed default CSS nth-child rules in favor of these if present?
            // Easier: Just toggle a class.
            var idx = 0;
            $rows.each(function() {
                if($(this).css('display') !== 'none') {
                    $(this).removeClass('odd even');
                    if(idx % 2 === 0) $(this).addClass('odd');
                    else $(this).addClass('even');
                    idx++;
                }
            });
        }
    });
});
