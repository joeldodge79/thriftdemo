from sparts import vfb303, vservice
from sparts.tasks import thrift
from tutorial import Calculator, ttypes


class CalcProcTask(thrift.ThriftProcessorTask):
    PROCESSOR = Calculator.Processor


class MyTServerService(thrift.NBServerTask):
    DEFAULT_PORT = 9090
    SERVICE = Calculator


class MyService(vservice.VService):

    TASKS = [MyTServerService, CalcProcTask]
    OP_DISPATCH = {
        ttypes.Operation.ADD: lambda x, y: x + y,
        ttypes.Operation.SUBTRACT: lambda x, y: x - y,
        ttypes.Operation.MULTIPLY: lambda x, y: x * y,
        ttypes.Operation.DIVIDE: lambda x, y: x / y}

    def calculate(self, logid, work):
        print logid, work
        try:
            return self.OP_DISPATCH[work.op](work.num1, work.num2)
        except ArithmeticError as ex:
            raise ttypes.InvalidOperation(what=1, why=str(ex))


if __name__ == '__main__':
    MyService.initFromCLI()
