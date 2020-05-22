var Toolset = Toolset || {};

Toolset.Gui = Toolset.Gui || {};

/**
 * Toolset Troubleshooting page controller.
 * 
 * This object is also acting as the main viewmodel which holds the collection of the troubleshooting section viewmodels.
 * 
 * @param $
 * @constructor
 * @since m2m
 */
Toolset.Gui.TroubleshootingPage = function($) {

    var self = this;

    // Parent constructor
    Toolset.Gui.AbstractPage.call(self);


    /**
     * Augment self, turning it into the main viewmodel for the page.
     * 
     * @returns {Toolset.Gui.TroubleshootingPage}
     * @since m2m
     */
    self.getMainViewModel = function() {

        self.isActionInProgress = ko.observable(false);

        var modelData = self.getModelData();
        
        // Create viewmodels for individual troubleshooting sections.
        // Model fields are descibed in the toolset_get_troubleshooting_sections filter inline doc.
        var sectionVMs = _.map(modelData.sections, function(sectionModel) {

            /**
             * A single section viewmodel, which binds to the "postbox" elements.
             * 
             * @type {sectionViewmodel}
             */
            var sectionViewmodel = new function() {
                
                var vm = this;

                // Read-only properties
                vm.title = sectionModel.title;
                vm.description = sectionModel.description;
                vm.buttonLabel = sectionModel.button_label;
                vm.isDangerous = sectionModel.is_dangerous;

                /** True if the user has clicked on the "I know what I'm doing" checkbox */
                vm.userConfirmed = ko.observable(false);

                /** Is the action in progress at the moment? */
                vm.isInProgress = ko.observable(false);

                /** True once the action has been completed. */
                vm.isCompleted = ko.observable(false);
                
                /**
                 * Determine whether the action can be started.
                 */
                vm.canProceed = ko.computed(function() {
                    return (
                        !self.isActionInProgress()
                        && (!vm.isDangerous || vm.userConfirmed())
                    );
                });


                /**
                 * Compute the CSS selector for the Start button.
                 */
                vm.buttonClass = ko.pureComputed(function() {
                    if(vm.canProceed()) {
                        if(vm.isDangerous) {
                            return 'button button-primary toolset-red-button';
                        } else {
                            return 'button button-primary';
                        }
                    } else {
                        return 'button-secondary';
                    }
                });


                /** Textual output from the AJAX calls to be displayed to the user. */
                vm.output = ko.observable('');


                vm.isOutputEmpty = ko.computed(function() {
                    return (0 === vm.output().length);
                });


                /** Append a new line to the output. */
                vm.appendToOutput = function(text) {
                    var currentContent = (vm.isOutputEmpty() ? '' : vm.output() + "\n");
                    vm.output(currentContent + text);
                };
                

                /** Initiate the action. */
                vm.onClick = function() {

                    vm.output('');
                    vm.isCompleted(false);

                    var initialActionData = {
                        action: sectionModel.action_name,
                        wpnonce: sectionModel.nonce
                    };

                    self.doAction(vm, initialActionData, sectionModel);

                };
                
            };

            return sectionViewmodel;

        });


        /**
         * Perform the (possibly multi-step) action.
         *
         * Starts with an AJAX call as defined by the section VM. Depending on what the AJAX response is, 
         * further calls may be performed until the "continue" flag is no longer true.
         * 
         * This is well-described by the toolset_get_troubleshooting_sections filter.
         * 
         * @param vm Section ViewModel.
         * @param {{action:string, wpnonce:string}} initialActionData Base for the "data" property for the AJAX call arguments.  
         * @param {{ajax_arguments:object}} initialArgumentSource Object holding the "argument source" whose properties will be appended to 
         *    the "data" mentioned above.
         * @since m2m
         */
        self.doAction = function(vm, initialActionData, initialArgumentSource) {

            // Indicate the activity
            vm.isInProgress(true);
            self.isActionInProgress(true);

            // Prepare helper functions
            var indicateActivityEnd = function() {
                vm.isCompleted(true);
                vm.isInProgress(false);
                self.isActionInProgress(false);
            };

            // From the "argument source" and initialActionData, build a new actionData object.
            var maybeAppendAjaxArguments = function(source) {
                var actionData = _.extend(initialActionData);
                if(_.has(source, 'ajax_arguments')) {
                    actionData = _.extendOwn(actionData, source['ajax_arguments']);
                }
                return actionData;
            };


            // Parse a message from the response or return a default one.
            var getMessage = function(responseData, defaultValue) {
                var message = (
                    _.has(responseData, 'message') && responseData.message.length > 0
                        ? responseData.message
                        : defaultValue
                );
                return message;
            };


            // Callback on failed AJAX call. Print the error and finish.
            var failCallback = function(response, responseData) {
                var message = getMessage(responseData, 'An unknown error has happened.');
                vm.appendToOutput(message);
                indicateActivityEnd();
            };

            var actionData = maybeAppendAjaxArguments(initialArgumentSource);

            // Process one step at a time until the AJAX call doesn't return the "continue" flag in the response.
            //
            // For explanation about how we're processing asynchronous AJAX calls in a loop but without risking
            // a stack overflow, look here: http://metaduck.com/01-asynchronous-iteration-patterns.html
            (function oneStep(actionData) {

                // Perform the async call
                $.post({
                    url: ajaxurl,
                    data: actionData,
                    success: function (originalResponse) {
                        var response = WPV_Toolset.Utils.Ajax.parseResponse(originalResponse);

                        // noinspection JSUnusedAssignment
                        self.debug('AJAX response', actionData, originalResponse);

                        if (response.success) {

                            // Inform the user
                            var responseData = response.data;
                            var message = getMessage(responseData, 'Action completed.');
                            vm.appendToOutput(message);
                            console.log(responseData);

                            // Does the response contain instructions about next step?
                            var shouldContinue = _.has(responseData, 'continue') && responseData.continue;

                            if(shouldContinue) {
                                // Build actionData for next step and execute it
                                var actionData = maybeAppendAjaxArguments(responseData);

                                // This will be called immediately after the stack unwinds,
                                // preventing a stack overflow even for very high number of steps.
                                setTimeout(_.partial(oneStep, actionData), 0);

                            } else {
                                // No next steps, we're successfully done here.
                                indicateActivityEnd();
                            }

                        } else {
                            failCallback(response, response.data || {});
                        }
                    },

                    error: function (ajaxContext) {
                        console.log('Error:', ajaxContext.responseText);
                        failCallback({success: false, data: {}}, {});
                    }
                });

            })(actionData);
        };
        
        
        self.sections = ko.observableArray(sectionVMs);

        
        // Initialize the main viewmodel.
        ko.applyBindings(self);
        return self;
    };


    self.beforeInit = function() {
        self.addKnockoutBindingHandlers();
    };


    /**
     * Add custom Knockout bindings.
     */
    self.addKnockoutBindingHandlers = function() {

        // Update textarea's value and scroll it to the bottom.
        var valueScroll = function(element, valueAccessor) {
            var value = ko.unwrap(valueAccessor());
            var textarea = $(element);

            textarea.val(value);
            textarea.scrollTop(textarea[0].scrollHeight);
        };
        ko.bindingHandlers.valueScroll = {
            init: valueScroll,
            update: valueScroll
        };

    };


    self.afterInit = function() {

        // Discourage the user from leaving the page while an action is in progress.
        WPV_Toolset.Utils.setConfirmUnload(
            function() {
                return self.isActionInProgress(); 
            },
            null,
            self.getString('confirmUnload')
        );

    }

};

// Make everything happen.
Toolset.Gui.troubleshootingPage = new Toolset.Gui.TroubleshootingPage(jQuery);
jQuery(document).ready(Toolset.Gui.troubleshootingPage.init);