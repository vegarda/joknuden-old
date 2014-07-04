#
#    Copyright (c) 2009 Tom Keffer <tkeffer@gmail.com>
#
#    See the file LICENSE.txt for your full rights.
#
#    $Revision: 1459 $
#    $Author: mwall $
#    $Date: 2013-10-08 17:44:50 -0700 (Tue, 08 Oct 2013) $
#
"""Package weeplot. A set of modules for doing simple plots

"""
# Define possible exceptions that could get thrown.

class ViolatedPrecondition(StandardError):
    """Exception thrown when a function is called with violated preconditions.
    
    """
